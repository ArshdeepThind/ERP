<template>
    <div class="row">
        <div class="col-sm-4">
            <h3>Roles</h3>
            <div class="list-group">
              <a href="javascript:void(0);" 
                :class="{ active: role==selectedRole, 'list-group-item': true }"
                v-for="role in roleList"
                @click="getRolePermissions(role)">
                <h4 class="list-group-item-heading">{{ role.name }}</h4>
                <p class="list-group-item-text">{{ role.label }}</p>
              </a>
            </div>
        </div>
        <div class="col-sm-4" v-if="selectedRole.length != 0">
            <h3>Role Permissions :{{selectedRole.name | capitalize }} </h3>
            <div class="list-group" v-if="selectedRolePermissions.length!=0">
              <a href="javascript:void(0);" class="list-group-item" 
                v-for="rolePermission in selectedRolePermissions"
                @click="detach(rolePermission)">
                <h4 class="list-group-item-heading"><i class="fa fa-fw fa-minus-square"></i> {{ rolePermission.label }} </h4>
              </a>
            </div>
            <div class="list-group" v-else>
                <a href="javascript:void(0);" class="list-group-item" >
                    <h4 class="list-group-item-heading">Aha! No permission assigned yet!</h4>
                  </a>
            </div>
        </div>
        <div class="col-sm-4" v-else>
            <h3>Role Permissions</h3>
            <div class="list-group">
              <a href="javascript:void(0);" class="list-group-item list-group-item-info">
                <h4 class="list-group-item-heading">Select Role to assign permission!</h4>
              </a>
            </div>
        </div>
        <div class="col-sm-4">
            <h3>Permissions</h3>
            <div class="list-group">
              <a href="javascript:void(0);" class="list-group-item" 
                v-for="permission in filteredPermissions" 
                @click="attach(permission)">
                <h4 class="list-group-item-heading"><i class="fa fa-fw fa-plus-square"></i> {{ permission.label }} </h4>
              </a>
            </div>
        </div>
    </div>
</template>

<script>
    Array.prototype.diff = function(a) {
        return this.filter(function(i) {return a.indexOf(i) < 0;});
    };
    export default {
        data(){
            return {
                permissionsList:[],
                roleList:[],
                selectedRole:[],
                selectedRolePermissions:[]
            };
        },
        mounted() {
            this.permissions();
            this.roles();
        },
        methods:{
            permissions(){
                let uri=`/admin/permissions/all`;
                this.$http.get(uri).then((response)=>{
                    let res= response.data;
                    if(res.status_code==200){
                        this.permissionsList = res.data;
                    }
                })
                .catch((error)=>{console.log(error)});
            },
            roles(){
                let uri=`/admin/roles/all`;
                this.$http.get(uri).then((response)=>{
                    let res= response.data;
                    if(res.status_code==200){
                        this.roleList=res.data;
                    }
                })
                .catch((error)=>{console.log(error)});
            },
            getRolePermissions(role){
                this.selectedRole=role;
                let uri=`/admin/role/${this.selectedRole.id}/permissions`;
                this.$http.get(uri).then((response)=>{
                    let res= response.data;
                    if(res.status_code==200){
                        this.selectedRolePermissions=res.data;
                    }
                })
                .catch((error)=>{console.log(error)});
            },
            detach(permission){
                if(this.selectedRole.length!=0){
                    let roleSelected=this.selectedRole;
                    let uri='/admin/detachPermission';
                    this.$http.post(uri,{permissionID:permission.id,roleID:roleSelected.id}).then((response)=>{
                        let res= response.data;
                        if(res.status_code==200){
                            this.selectedRolePermissions=res.data;
                        }
                    })
                    .catch((error)=>{console.log(error)});
                }
            },
            attach(permission){
                if(this.selectedRole.length!=0){
                    let roleSelected=this.selectedRole;
                    let uri='/admin/assignPermission';
                    this.$http.post(uri,{permissionID:permission.id,roleID:roleSelected.id}).then((response)=>{
                        let res= response.data;
                        if(res.status_code==200){
                            this.selectedRolePermissions=res.data;
                        }
                    })
                    .catch((error)=>{console.log(error)});
                }
            }
        },
        computed:{
            filteredPermissions(){
                let a = this.permissionsList;
                let b = this.selectedRolePermissions;
                var onlyInA = a.filter(function(current){
                    return b.filter(function(current_b){
                        return current_b.id == current.id
                    }).length == 0
                });

                var onlyInB = b.filter(function(current){
                    return a.filter(function(current_a){
                        return current_a.id == current.id
                    }).length == 0
                });
                return onlyInA.concat(onlyInB);
            } 
        },
        filters: {
            capitalize(str) {
              return str.charAt(0).toUpperCase() + str.slice(1)
            }
        }
    }
</script>
