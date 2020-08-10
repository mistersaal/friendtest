import _ from 'lodash'
window._ = _

import axios from 'axios'
window.axios = axios
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
window.axios.defaults.headers.common['X-Vk-Auth-Url'] = window.location.href

import bridge from '@vkontakte/vk-bridge'
window.bridge = bridge
