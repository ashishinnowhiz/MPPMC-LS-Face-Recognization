'use strict';
import { ApiService } from './core/api.js';
import { CanvasManager } from './modules/canvas.js';
import { SaveManager } from './modules/save.js';

class EvaluationApp {
    constructor() {
        this.api = new ApiService(window.base_url || '/');
        this.canvas = new CanvasManager();
        this.saveManager = new SaveManager(this.api);
        this.initialize();
    }
    initialize() {
        console.log('Evaluation App Initialized');
        this.bindEvents();
    }
    bindEvents() {
        const saveButton = document.getElementById('finishBtn');
        const evalButton = document.getElementById('evalButton');

        if (saveButton) {
            saveButton.addEventListener(
                'click',
                this.handleSave.bind(this)
            );
        }
       if (evalButton) {
            evalButton.addEventListener(
                'click',
                async (event) => {
                    event.preventDefault();
                    await this.getSheet();
                }
            );
    }
 } //events binding end
   async getSheet(){
        try {
            console.log('Loading Sheet...');
            const response = await fetch(BSEURL+'sheet/get', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            });
            if (!response.success) {
                throw new Error('Failed to load sheet');
            }
            const data = await response.json();
            console.log(data);
        } catch (error) {
            console.error(error);
            alert(error.message);
        }
    }
    async handleSave() {
        try {
            const payload = {
                timestamp: Date.now()
            };
            const response = await this.saveManager.saveEvaluation(payload);
            console.log('Saved:', response);
        } catch (error) {
            console.error(error);
            alert(error.message);
        }
    }
}
window.addEventListener('DOMContentLoaded', () => {
    new EvaluationApp();
});

