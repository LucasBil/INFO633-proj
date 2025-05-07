class API {
    constructor(baseURL = "http://localhost:8081/") {
        const cookieManager = new CookiesManager();
        this.BASE_URL = baseURL;
        this.defaultHeaders = {
            'Content-Type': 'application/json',
            'Authorization' : `Bearer ${cookieManager.getCookie('token')}`,
        };
    }

    async request(method, url, data = null, config = {}) {
        const headers = { ...this.defaultHeaders, ...config.headers };
        const requestOptions = {
            method: method,
            mode: 'cors',
            headers: headers
        };

        if (data) {
            requestOptions.body = JSON.stringify(data);
        }

        try {
            const response = await fetch(`${this.BASE_URL}${url}`, requestOptions);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return await response.json();
            } else {
                return await response.text();
            }
        } catch (error) {
            this.handleError(error);
            throw error;
        }
    }

    async get(url, config = {}) {
        return this.request('GET', url, null, config);
    }

    async post(url, data = {}, config = {}) {
        return this.request('POST', url, data, config);
    }

    async put(url, data = {}, config = {}) {
        return this.request('PUT', url, data, config);
    }

    async delete(url, config = {}) {
        return this.request('DELETE', url, null, config);
    }

    handleError(error) {
        console.error('API Error:', error.message);
    }
}