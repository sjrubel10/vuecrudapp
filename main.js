var app = new Vue({
    el: "#app",
    data: {
        errorMsg:"",
        successMsg:"",
        showAddModel:false,
        showEditModel:false,
        showDeleteModel:false,
        isDataLoad:false,
        isDataLoaded:true,
        users:[],
        useID:[],
        newUser:{name:"",email:"",phone:"",file:""},
        currentuser:{},
        alreadyLoaded:"",
        showLoadMoreBtn:false,
    },
    mounted:function() {
        this.getAllUsers();
    },
    methods: {
      getAllUsers(){
        axios.post("http://localhost/vuecrudapp/process.php?action=read",{alreadyLoadedData: ""}).
        // axios({
        //     url: "http://localhost/vuecrudapp/process.php?action=read",
        //     method: "POST",
        //     // obj,
        //     data: {
        //       firstName: 'Fred',
        //       lastName: 'Flintstone'
        //     },
        //     // Attaching the form data
        //   }).
          then(function(response){
              if(response.data.error){
                  app.errorMsg=response.data.message;
              }else{
                  app.isDataLoaded=false;
                  app.isDataLoad=true;
                  app.users=response.data.users;
                  app.alreadyLoaded=app.getLoadedIds(response.data.users, "id");
                  if(app.alreadyLoaded.length>=5){
                    app.showLoadMoreBtn=true;
                  }
              }
          });
      },
      addUser(){
        var FormData=app.toFromdata(app.newUser);
        axios.post("http://localhost/vuecrudapp/process.php?action=create",FormData).then(function(response){
            app.newUser={name:"",email:"",phone:"",file:""};
            if(response.data.error){
                app.errorMsg=response.data.message;
            }else{
                app.successMsg=response.data.message;
                app.getAllUsers();
            }
        });
      },
      updateUser(){
        var FormData=app.toFromdata(app.currentuser);
        axios.post("http://localhost/vuecrudapp/process.php?action=update",FormData).then(function(response){
            app.currentuser={};
            if(response.data.error){
                app.errorMsg=response.data.message;
            }else{
                app.successMsg=response.data.message;
                // app.getAllUsers();
            }
        });
      },
      deleteUser(){
        var FormData=app.toFromdata(app.currentuser);
        let clickedId=app.currentuser.id;
        axios.post("http://localhost/vuecrudapp/process.php?action=delete",FormData).then(function(response){
            app.currentuser={};
            if(response.data.error){
                app.errorMsg=response.data.message;
            }else{
                app.successMsg=response.data.message;
                // app.getAllUsers();
            }
        });
      },
      toFromdata(obj){
        var fd=new FormData();
        for(var i in obj){
            fd.append(i,obj[i]);
        }
        return fd;
      },
      selectUser(user){
        app.currentuser=user;
      },
      hideDeleteUser(){
        var deletedId=app.currentuser.id;
        // get id column values
        var ids=app.getLoadedIds(app.users, "id");
        // get key from ids value
        var key=app.getKeyByValue(ids, deletedId);
        app.hideDeleteddata(key);
      },
      getKeyByValue(object, value) {
        return Object.keys(object).find(key => object[key] === value);
      },
      hideDeleteddata(i) {
        this.users.splice(i, 1);
      },
      select: function(event) {
        targetId = event.currentTarget.id;
      },
      getLoadedIds(array, column) {
        return array.map((item) => item[column]);
      },
      onChange(e) {
        app.newUser.file = e.target.files[0];
      },
      getData(limit=5){
        var loaded=app.getLoadedIds( app.users, "id").toString();
        app.alreadyLoaded='"'+loaded+'"';
        axios.post("http://localhost/vuecrudapp/process.php?action=read",{
          alreadyLoadedData: this.alreadyLoaded,
          displayLimit:limit,
        }).
        then(function(response){
            if(response.data.error){
                app.errorMsg=response.data.message;
            }else{
                app.isDataLoaded=false;
                app.isDataLoad=true;
                // console.log(response.data.users);
                if(response.data.users.length<5){
                  app.showLoadMoreBtn=false;
                }
                response.data.users.forEach(element => {
                    app.users.push(element);
                });
            }
        });
      },
      

    },
});