<?php


namespace App\Services;

use App\Exceptions\TestAnswerException;
use App\QuestionAnswer;
use App\Test;
use App\TestAnswer;
use App\User;
use Illuminate\Support\Collection;

class TestAnswerService
{
    protected $testService;

    public function __construct(TestService $service)
    {
        $this->testService = $service;
    }

    public function getUserTestForResponder(User $author, User $responder): ?Test
    {
        $test = $this->testService->getUserTest($author);
        if (!$test) {
            return null;
        }
        $test->yourAnswers = $this->getAnswers($test, $responder);
        if (!$test->yourAnswers) {
            $test->questions->makeHidden('answer');
        }
        return $test;
    }

    private function getAnswers(Test $test, User $user): ?Collection
    {
        $answer = $test->userAnswer($user);
        return $answer ? $answer->questionAnswers->keyBy('question_id') : null;
    }

    /**
     * @param Test $test
     * @param User $user
     * @param Collection $answers
     * @param bool $anonymously
     * @throws TestAnswerException
     */
    public function answer(Test $test, User $user, Collection $answers, bool $anonymously): void
    {
        $questions = $test->questions->keyBy('id');
        if (!$this->checkQuestionsAndAnswer($questions, $answers)) {
            throw new TestAnswerException('Несовпадение вопросов.');
        }

        $testAnswer = $this->createTestAnswer($test, $user, $anonymously);
        $this->createQuestionAnswers($testAnswer, $questions, $answers);
    }

    private function createTestAnswer(Test $test, User $user, bool $anonymously): TestAnswer
    {
        $testAnswer = new TestAnswer();
        $testAnswer->anonymously = $anonymously;
        $testAnswer->responder()->associate($user);
        $test->testAnswers()->save($testAnswer);
        return $testAnswer;
    }

    private function createQuestionAnswers(TestAnswer $testAnswer, Collection $questions, Collection $answers): void
    {
        $questionAnswers = new Collection();
        foreach ($answers as $answer) {
            $questionAnswer = new QuestionAnswer();
            $questionAnswer->answer = $answer['answer'];
            $questionAnswer->question()->associate($questions[$answer['question_id']]);
            $questionAnswers->push($questionAnswer);
        }
        $testAnswer->questionAnswers()->saveMany($questionAnswers);
    }

    private function checkQuestionsAndAnswer(Collection $questions, Collection $answer)
    {
        $count = 0;
        return $answer->each(function ($answer) use ($questions, &$count) {
            $count++;
            return $questions->has($answer['question_id']);
        }) && $count === $questions->count();
    }
}
