window.nameParam = 'name'
window.passwordParam = 'password'
window.redir = (where) => window.location.replace(where)

window.setAllToDark = () => {
    document.querySelector('body').style.backgroundColor = 'black'
    try { document.querySelector('h1').style.color = 'white' } catch (_) {}
    try { document.querySelectorAll('button').forEach(i => {
        i.style.color = 'white'
        i.style.backgroundColor = 'black'
    }) } catch (_) {}
    try { document.querySelectorAll('span').forEach(i => i.style.color = 'white') } catch (_) {}
}

window.isDark = () => {
    const request = new XMLHttpRequest()
    request.open('GET', '/session.php', false)
    request.send(null)
    return request.responseText === 'true'
}