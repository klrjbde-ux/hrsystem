/**
 * Load this project's JavaScript dependencies
 */
require("./bootstrap");

/**
 * Import Employee JS
 * (Search, Delete confirmation, Success message)
 */
import "./employee.js";

/**
 * Vue setup (optional — remove if you are not using Vue components)
 */
window.Vue = require("vue").default;

Vue.component(
    "example-component",
    require("./components/ExampleComponent.vue").default
);

const app = new Vue({
    el: "#app",
});

/**
 * NOTE: FullCalendar code removed because it causes build errors.
 * If you need a calendar later, you can re-add it using the new FullCalendar v6 import method:
 * import { Calendar } from '@fullcalendar/core';
 * import dayGridPlugin from '@fullcalendar/daygrid';
 * import interactionPlugin from '@fullcalendar/interaction';
 * import '@fullcalendar/common/main.css';
 * import '@fullcalendar/daygrid/main.css';
 */
