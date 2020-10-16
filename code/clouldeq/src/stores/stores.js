import Vue from 'vue';
import Vuex from "vuex";

Vue.use(Vuex);

const state = {
    timelinedata: [],
    chartdata: [],
    gradeData: [],
    linedata: []
}

export default new Vuex.Store({
    state
});