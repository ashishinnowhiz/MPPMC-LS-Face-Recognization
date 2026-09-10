'use strict';

import { state } from '../core/state.js';

export class SaveManager {

    constructor(apiService) {
        this.api = apiService;
    }

    async saveEvaluation(payload) {

        if (state.isSaving) {
            return;
        }

        state.isSaving = true;

        try {

            const response = await this.api.request(
                'sheet/save',
                'POST',
                payload
            );

            if (!response.success) {
                throw new Error(response.message || 'Save failed');
            }

            return response;

        } finally {
            state.isSaving = false;
        }
    }
}
