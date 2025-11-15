<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LaravelApiWithAxios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<style>
    body{
        padding: 50px;
    }
</style>
<body>
  <div class="container">
    <h4>Posts</h4>
    <span id="successMsg"></span>
    <div class="row">
        <div class="col-md-8">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tablebody">
                    
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <h4>Creat Post</h4>
            <span id="success"></span>
            <form name="myForm">
            <div class="form-group">
                <label for="">Title</label>
                <input type="text"name="title" class="form-control">
                <span id="titleError"></span>
            </div>
          
            <div class="form-group">
                <label for="">Description</label>
                <textarea name="description" id="" class="form-control" rows="5">
                   
                </textarea>
                 <span id="descError"></span>
            </div>
            <br>
            <button type="submit" class="btn btn-primary btn-block">Submit</button>
            </form>
        </div>
    </div>
  </div>
    
    <!-- Modal -->
  <!-- Only one Edit Modal -->
<div class="modal fade" id="EditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Post</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form name="editForm" id="editForm">
        <div class="modal-body">
          <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control">
            <span id="titleError"></span>
          </div>

          <div class="form-group mt-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="5"></textarea>
            <span id="descError"></span>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>


    {{-- js --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    {{-- Axios --}}
  <script src="https://cdn.jsdelivr.net/npm/axios@1.6.7/dist/axios.min.js"></script>
    <script>
      var tablebody = document.getElementById('tablebody');
      var titleList = document.getElementsByClassName('titleList');
      var descList = document.getElementsByClassName('descList');
      
        //get
       axios.get('/api/posts')
       .then(response =>{
       response.data.forEach(item=>{
        displayData(item);
     
       });
       console.log(response.data);
    })
       .catch(error=> console.log(error));


       //create
       var myForm =document.forms['myForm'];
       var titleInput = myForm['title'];
       var descriptionInput = myForm['description'];

       myForm.onsubmit = function(event){
        event.preventDefault();

        axios.post('/api/posts',{
            title: titleInput.value,
            description: descriptionInput.value
        })
            .then(response=> {
                console.log(response.data);
                if(response.data.msg == 'created is succefully'){
                alertMsg(response.data.msg);
                    myForm.reset();
                    displayData(response.data[0]);

                    
                }else{
                var titleError = document.getElementById('titleError');
                var descError = document.getElementById('descError');
                 
                titleError.innerHTML = titleInput.value == '' ? '<i class="text-danger">'+response.data.msg.title +'</i>' : '';

                descError.innerHTML = descriptionInput.value == '' ? '<i class="text-danger">'+response.data.msg.description+'</i>' : '';     
                     
                }
                
                
            })
            .catch(error=>console.log(error));
       
       }
       //Edit 

       var editForm = document.forms['editForm'];
       var editTitleInput = editForm['title'];
       var editdescInput = editForm['description'];
       var postIdToUpdate;
       var oldTitle;

       function editBtn(postId){
        postIdToUpdate = postId;
       axios.get('api/posts/'+postId)
            .then(response => {
                console.log(response.data.title, response.data.description);
                editTitleInput.value = response.data.title;
                editdescInput.value = response.data.description;

                oldTitle = response.data.title;
            })
            .catch(error => console.log(error));
       }

       //update

       editForm.onsubmit = function(event){
        event.preventDefault();
        axios.put('api/posts/'+postIdToUpdate,{
          title: editTitleInput.value,
          description: editdescInput.value,
        })
            .then(response => {
          alertMsg(response.data.msg);
              let modalEl = document.getElementById('EditModal');
              let modal = bootstrap.Modal.getInstance(modalEl);
              modal.hide();

              let row = document.getElementById("row_" + postIdToUpdate);

       
              for(var i=0; i<titleList.length; i++){
                if(titleList[i].innerHTML == oldTitle){
                  titleList[i].innerHTML = editTitleInput.value;
                  descList[i].innerHTML = editdescInput.value;
                } 

              }

            })
              .catch(error => console.log(error));
            
        
       }

       //delete
       function deleteBtn(postId){
       axios.delete('api/posts/'+postId)
            .then(response=>{
              
              alertMsg(response.data.msg);
            })
            .catch(error=> console.log(error));

       }

       //helper functions
     function displayData(data){
     tablebody.innerHTML += 
        '<tr id="row_'+data.id+'">'+
            '<td>'+data.id+'</td>'+
            '<td class="titleList">'+data.title+'</td>'+
            '<td class="descList">'+data.description+'</td>'+
            '<td>'+
                '<button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#EditModal" onclick="editBtn('+data.id+')">Edit</button>'+
                '<button class="btn btn-danger ml-2" onclick="deleteBtn('+data.id+')">Delete</button>'+
            '</td>'+
        '</tr>';
}

      function alertMsg(Msg){
          document.getElementById('successMsg').innerHTML = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong>'+Msg+'</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button></div>';
    }


       
       
    </script>
</body>
</html>