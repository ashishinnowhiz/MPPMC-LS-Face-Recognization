'use strict';

export class ApiService {
    constructor(baseUrl, csrfToken = '') {
        this.baseUrl = baseUrl;
        this.csrfToken = csrfToken;
        this.pendingRequests = new Map();
    }

    async request(endpoint, method = 'POST', data = {}) {
        const requestId = `${endpoint}_${Date.now()}`;

        try {
            const response = await fetch(`${this.baseUrl}${endpoint}`, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: method !== 'GET'
                    ? JSON.stringify(data)
                    : undefined
            });

            if (!response.ok) {
                throw new Error(`HTTP Error: ${response.status}`);
            }

            return await response.json();

        } catch (error) {
            console.error('API Error:', error);
            throw error;
        } finally {
            this.pendingRequests.delete(requestId);
        }
    }
}
