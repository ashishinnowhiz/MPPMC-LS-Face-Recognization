'use strict';

import { state } from '../core/state.js';

export class CanvasManager {

    constructor() {
        this.bindEvents();
    }

    bindEvents() {
        document.addEventListener('mousedown', this.handleMouseDown.bind(this));
        document.addEventListener('mouseup', this.handleMouseUp.bind(this));
    }

    handleMouseDown(event) {
        const canvas = event.target.closest('.pageCanvas');

        if (!canvas) return;

        state.activeCanvas = canvas.id;
    }

    handleMouseUp(event) {
        const canvas = event.target.closest('.pageCanvas');

        if (!canvas) return;

        console.log('Canvas interaction complete');
    }

    clear(canvasId) {
        const canvas = document.getElementById(canvasId);

        if (!canvas) return;

        const ctx = canvas.getContext('2d');

        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }
}
