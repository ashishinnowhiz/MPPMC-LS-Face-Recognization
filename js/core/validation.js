'use strict';

export class Validator {

    static sanitizeText(text = '') {
        return String(text)
            .replace(/[<>]/g, '')
            .trim()
            .substring(0, 500);
    }

    static validateScore(score) {
        const num = Number(score);

        if (!Number.isFinite(num)) {
            throw new Error('Invalid score');
        }

        return num;
    }

    static validateCoordinates(x, y) {
        return Number.isFinite(x) && Number.isFinite(y);
    }
}
