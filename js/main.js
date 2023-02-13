
const psw_field = document.querySelector('.form input[type="password"]'),
      psw_show = document.querySelector('.form .field .show');

const form = document.querySelector('form'),
      error = document.querySelector('.error-txt')

// function for show an hide password
if(psw_field != null){
  psw_show.onclick = ()=>{
    if(psw_field.type == 'password'){
      psw_field.type = 'text';
  
    }else{
      psw_field.type = 'password'
    }
  }
}


if(form != null){
  form.onsubmit = (e)=>{
    e.preventDefault();
    let page = form.dataset.page
    //start Ajax
    let xhr = new XMLHttpRequest();
    xhr.open('post', "connect/connect.php?"+page, true)
    xhr.onload = ()=>{
      if(xhr.readyState==4 && xhr.status==200){
        let data = xhr.response;
        console.log(data)
        let out = JSON.parse(xhr.response);
        if(out['status'] == 1){
          console.log(out['message']);
          location.href = 'user.php';
  
        }else{
          error.innerHTML = out['message'];
          error.style.display = 'block';
        }
      }
    }
    let form_data = new FormData(form);
    console.log(form_data)
    xhr.send(form_data);
  }
}


function xml(){

  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
      if (this.readyState==4 && this.status==200) {
        console.log(this.responseText)
          resp = JSON.parse(this.responseText);
          
          inc_data(resp, 'all');               // send data to add it in DOM
  
          change_row();                       // change row style
          bts_act();                          // change buttons of multi action
      }
  }
  xmlhttp.open("GET","connect_all.php?page=users&showUser="+val,true);
  xmlhttp.send();
}