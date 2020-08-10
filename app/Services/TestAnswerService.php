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
        $test->yourAnswer = $this->getAnswers($test, $responder);
        if (!$test->yourAnswer) {
            $test->questions->makeHidden('answer');
        }
        return $test;
    }

    private function getAnswers(Test $test, User $user): ?Collection
    {
        $answer = $test->userAnswer($user);
        return $answer ? collect([
            'answers' => $answer->questionAnswers->keyBy('question_id'),
            'id' => $answer->id
        ]) : null;
    }

    /**
     * @param Test $test
     * @param User $user
     * @param Collection $answers
     * @param bool $anonymously
     * @return int
     * @throws TestAnswerException
     */
    public function answer(Test $test, User $user, Collection $answers, bool $anonymously): int
    {
        $questions = $test->questions->keyBy('id');
        if (!$this->checkQuestionsAndAnswer($questions, $answers)) {
            throw new TestAnswerException('Несовпадение вопросов.');
        }

        $testAnswer = $this->createTestAnswer($test, $user, $anonymously);
        $this->createQuestionAnswers($testAnswer, $questions, $answers);

        return $testAnswer->id;
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

    public function getTestAnswersInfo(User $user): array
    {
        $answers = $user->test->testAnswers()->with(['questionAnswers.question', 'responder'])->get();
        $answers->makeHidden('questionAnswers');
        foreach ($answers as $answer) {
            if ($answer->anonymously) {
                $answer->makeHidden('responder');
            }
            $answer->right = 0;
            $answer->count = 0;
            foreach ($answer->questionAnswers as $questionAnswer) {
                /** @var QuestionAnswer $questionAnswer */
                if ($questionAnswer->answer === $questionAnswer->question->answer) {
                    $answer->right++;
                }
                $answer->count++;
            }
        }
        return compact('answers');
    }
}
