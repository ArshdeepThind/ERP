
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));
Vue.component('alert', require('./components/Alert.vue'));
Vue.component('AuthValidate', require('./components/AuthValidation.vue'));
Vue.component('ImgFileinput', require('./components/ImgFileinput.vue'));


Vue.component('roles', require('./components/admin/Roles.vue'));
Vue.component('permissions', require('./components/admin/Permissions.vue'));
Vue.component('assign-permission', require('./components/admin/AssignPermission.vue'));
Vue.component('administrator', require('./components/admin/Administrator.vue'));

Vue.component('CsmPages', require('./components/admin/CmsPages.vue'));
Vue.component('CmsCategory', require('./components/admin/CmsCategory.vue'));
Vue.component('AddCategory', require('./components/admin/AddCategory.vue'));
Vue.component('AddSubcategory', require('./components/admin/AddSubcategory.vue'));
Vue.component('CmsSubcategory', require('./components/admin/CmsSubcategory.vue'));
Vue.component('Users', require('./components/admin/Users.vue'));
Vue.component('AddUser', require('./components/admin/AddUser.vue'));
Vue.component('AddCompany', require('./components/admin/AddCompany.vue'));
Vue.component('dc', require('./components/admin/dc.vue'));
Vue.component('handyman', require('./components/admin/handyman.vue'));
Vue.component('AddHandyman', require('./components/admin/AddHandyman.vue'));
Vue.component('Cars', require('./components/admin/Cars.vue'));
Vue.component('drivers', require('./components/admin/drivers.vue'));
Vue.component('handymans', require('./components/admin/handymans.vue'));
Vue.component('AddDriver', require('./components/admin/AddDriver.vue'));
Vue.component('AddHandy', require('./components/admin/AddHandy.vue'));


Vue.prototype.$http = axios;



window.Event= new class{
    constructor(){
        this.vue= new Vue();
    }

    fire(event, data=null){
        this.vue.$emit(event,data);
    }

    listen(event, callback){
        this.vue.$on(event,callback);
    }
}

const app = new Vue({
    el: '#app'
});
