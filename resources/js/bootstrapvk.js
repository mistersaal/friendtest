function previewed() {
    store.commit('preview')
}

bridge.subscribe(e => {
    if (e.detail.type === 'VKWebAppUpdateConfig') {
        const scheme = e.detail.data.scheme ? e.detail.data.scheme : 'client_light'
        if (scheme !== 'client_light' && scheme !== 'bright_light') {
            import('../sass/dark.scss')
        }
    }
    if (e.detail.type === 'VKWebAppAllowMessagesFromGroupFailed') {
        bridge.send("VKWebAppAllowMessagesFromGroup", {"group_id": 198966118, "key": "dBuBKe1kFcdemzB"});
    }


});
bridge.send("VKWebAppInit", {})

axios.get('/info').then((response) => {
    let user = response.data.user
    store.commit('setTest', user.hasTest)
    store.commit('setVkid', user.vkid)
    store.commit('loaded')
})

setTimeout(() => store.commit("init"), 1000)
bridge.send("VKWebAppShowNativeAds", {ad_format: "preloader"})
    .then(previewed)
    .catch(previewed)
bridge.send("VKWebAppGetClientVersion", {})
    .then(data => {
        if (data.platform === 'web' || data.platform === 'mobile-web') {
            previewed()
            store.commit('web')
        }
    });

    bridge.send("VKWebAppAllowMessagesFromGroup", {"group_id": 198966118, "key": "dBuBKe1kFcdemzB"});