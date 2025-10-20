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
    <div class="row">
        <div class="col-md-8">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
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
    



    {{-- js --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    {{-- Axios --}}
  <script src="https://cdn.jsdelivr.net/npm/axios@1.6.7/dist/axios.min.js"></script>
    <script>
        //get
       axios.get('/api/posts')
       .then(response =>{
        var tablebody = document.getElementById('tablebody');
       response.data.forEach(item=>{
        tablebody.innerHTML += 
                    '<tr>'+
                    '<td>'+item.id+'</td>'+
                    '<td>'+item.title+'</td>'+
                    '<td>'+item.description+'</td>'+
                    '<td>'+
                        '<button class="btn btn-success">Edit</button>'+
                       '<button class="btn btn-danger ml-2">Delete</button>'+
                       '</td>'+
                    '</tr>';
       });
       console.log(response.data);
    })
       .catch(error=> console.log(error));


       //post
       var myForm =document.forms['myForm'];
       var titleInput = myForm['title'];
       var descriptionInput = myForm['description'];

       myForm.onsubmit = function(event){
        event.preventDefault();

        axios.post('/api/posts',{
            title: titleInput.value,
            description: descriptionInput.value,
        })
            .then(response=> {
                console.log(response.data)
                if(response.data.msg == 'created is succefully'){
                    document.getElementById('success').innerHTML ='<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong>'+response.data.msg+'</strong> You should check in on some of those fields below.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button></div>'
                }else{
                   if(titleInput.value == ''){
                     document.getElementById('titleError').innerHTML='<i class="text-danger">'+response.data.msg.title+'</i>';
                   }
                   if(descriptionInput.value == ''){
                    document.getElementById('descError').innerHTML='<i class="text-danger">'+response.data.msg.description+'</i>';
                   }
                     
                }
                
                
            })
            .catch(error=>console.log(error));
        // console.log(titleInput.value);
        // console.log(descriptionInput.value);
       }

       
    </script>
</body>
</html>