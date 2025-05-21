export function getUserData() {
    return JSON.parse(
    decodeURIComponent(
        document.cookie.split('; ')
        .find(row => row.startsWith('user='))
        ?.split('=')[1] || '{}'
        )   
    );
}