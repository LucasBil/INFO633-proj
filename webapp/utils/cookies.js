class CookiesManager {
    constructor() {}

    setCookie(name, value, daysToLive = 7) {
        const encodedValue = encodeURIComponent(value);
        const date = new Date();
        date.setTime(date.getTime() + (daysToLive * 24 * 60 * 60 * 1000));
        const expires = "expires=" + date.toUTCString();
        document.cookie = `${name}=${encodedValue}; ${expires}; path=/`;
    }

    getCookie(name) {
        const cookieString = document.cookie;
        const cookies = cookieString.split('; ');
        
        for (const cookie of cookies) {
            const [cookieName, cookieValue] = cookie.split('=');
            if (cookieName === name) {
                return decodeURIComponent(cookieValue);
            }
        }
        
        return null;
    }

    deleteCookie(name) {
        document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
    }
}