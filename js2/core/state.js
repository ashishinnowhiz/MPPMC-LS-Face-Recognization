'use strict';

export const state = Object.seal({
    selectedTool: null,
    selectedQuestion: null,
    selectedPage: 1,
    totalScore: 0,
    markings: [],
    pageMarked: new Set(),
    isSaving: false,
    zoom: 100
});
