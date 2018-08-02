
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');

//var autoprefixer = require('autoprefixer');

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
Vue.component('users', require('./components/admin/Users.vue'));

Vue.component('chat', require('./components/employee/Chat.vue'));
Vue.component('employees', require('./components/employee/Employees.vue'));
Vue.component('jobs', require('./components/employee/Jobs.vue'));
Vue.component('add-employee', require('./components/employee/AddEmployee.vue'));
Vue.component('employee-leaves', require('./components/employee/EmployeeLeaves.vue'));
Vue.component('leaves', require('./components/employee/Leaves.vue'));
Vue.component('payment', require('./components/employee/Payment.vue'));
Vue.component('payment-history', require('./components/employee/PaymentHistory.vue'));
Vue.component('employee-attendence', require('./components/employee/EmployeeAttendence.vue'));

Vue.component('products', require('./components/inventory/Products.vue'));
Vue.component('supplier', require('./components/inventory/Supplier.vue'));
Vue.component('orders', require('./components/inventory/Orders.vue'));

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
    el: '#app',

    data: {
        chat_data_parent: {
            chat_employeelist: [],
            flag_load_chat: 1
        }
    },

    mounted() {
        this.LoadChatCaselist();
    },

    methods: {

        LoadChatCaselist() {
            const vm = this;

            firebase.database().ref("RECENTCHAT/" + window.Laravel.login_user_id).on("value", function(snapshot) {
                    
                    let tmp_chat_employeelist = [];
                    let chat_badgeCount = 0;

                    if (snapshot.val()) {
                        let datalist = snapshot.val();

                        _.each(datalist, function(element, key) {
                            tmp_chat_employeelist.push({
                                'badgeCount': element.badgeCount,
                                'conversation_id': element.conversation_id,
                                'senderID': element.senderID, 
                                'senderName': element.senderName,
                                'receiverID': element.senderID, 
                                'receiverName': element.receiverName,
                                'messageType': element.messageType,
                                'messageText': element.messageText,
                                'timestamp': element.timestamp
                            });

                            chat_badgeCount += element.badgeCount;
                        });
                    }
                    if (chat_badgeCount > 0) {
                        $("#chat_badgeCount").show();
                    } else {
                        $("#chat_badgeCount").hide();
                    }
                    $("#chat_badgeCount").html(chat_badgeCount);

                    vm.chat_data_parent.chat_employeelist = tmp_chat_employeelist.sort(function(a, b) {
                        if (a.timeStamp < b.timeStamp)
                            return 1;
                        if (a.timeStamp > b.timeStamp)
                            return -1;
                        return 0;
                    });

                    vm.chat_data_parent.flag_load_chat = 0;
                });
        }
    }
});
