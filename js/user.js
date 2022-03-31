// const for user list
const copy_cont = document.querySelector('.user-list .copy'),
      user_list = document.querySelector('.user-list')

// const for search fieald
const srch = document.querySelector('.search button'),
      srch_inp = document.querySelector('.search input');

// const for search resalt
const res_list = document.querySelector('.search .resulte'),
      res_user = document.querySelector('.search .copy');
const   res_non  = document.querySelector('.search .no-res');


let copy = copy_cont.cloneNode(true);
copy.classList = copy.dataset.class;
copy.removeAttribute('data-class');

// show and hide input search 
if(srch != null){
  srch.onclick = ()=>{
    srch.parentElement.classList.toggle('active')
    res_list.classList.remove('active')
    srch_inp.focus()
    srch_inp.value = '';
  }
}


let user = {
  name:   copy.querySelector('.name'),
  img:    copy.querySelector('img'),
  lastmsg:   copy.querySelector('.last'),
  stat:   copy.querySelector('.status-dot')
}

function setUser(data){
  
  for(user_info in data){
    let user_id = document.querySelector('.user-list .user-row[data-id="'+user_info+'"] .status-dot');
    
    if(user_id != null){

      if(data[user_info]['stat'] == 1){
        user_id.classList.add('active')

      }else{
        user_id.classList.remove('active')
      }

    }else{
      console.log(user_id)
      let href = copy.dataset.href;
      copy.setAttribute('href', href+"?id="+user_info) ;
      copy.dataset.id = user_info;

      user.name.innerHTML = data[user_info]['name'];
      user.img.src = "images/"+data[user_info]['img'];

      let msg = data[user_info]['lastmsg'];
      user.lastmsg.innerHTML = msg == null ? "No message" : msg;

      if(data[user_info]['stat'] == 1){
        user.stat.classList.add('active')

      }else{
        user.stat.classList.remove('active')
      }
      
      user_list.innerHTML += copy.outerHTML;
    }
  }
}

  
function getUser(){
  let xml=new XMLHttpRequest();
  xml.onreadystatechange=function() {
      if (this.readyState==4 && this.status==200) {
        resp = JSON.parse(this.responseText);
        console.log(resp)
        setUser(resp);
      }
  }
  xml.open("GET",'connect/chat.php?getUser',true);
  xml.send();
}
getUser();
//setInterval(getUser,3000);



// search function
srch_inp.onkeyup = function(){
  let srch = srch_inp.value;
  if(srch != ''){

    let xml=new XMLHttpRequest();
    xml.onload=function() {
        if (this.readyState==4 && this.status==200) {
          resp = JSON.parse(this.responseText);
          setSearch(resp);
        }
    }
    xml.open("POST","connect/chat.php?searchUser");
    xml.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xml.send("search="+srch);
  }else{
    res_list.classList.remove('active')
  }
}

let copy_srch = res_user.cloneNode(true);
copy_srch.classList = copy_srch.dataset.class;
copy_srch.removeAttribute('data-class');

let search = {
  img:  copy_srch.querySelector('img'),
  name: copy_srch.querySelector('.name')
}

function setSearch(data){
  if(srch_inp.value == ''){
    res_list.classList.remove('active')
    
  }else{
    res_list.classList.add('active');
    let user = res_list.querySelectorAll('.res-user'); user != null ? user.forEach(function(e){
      e.remove();
    }) : '';

    if(data == null){
      res_non.classList.add('show')

    }else{
      res_non.classList.remove('show')
      for(ele in data){
        copy_srch.dataset.id = ele
        copy_srch.setAttribute('href', copy_srch.dataset.href+'?id='+ele)
        search.img.src = "images/"+data[ele]['img']
        search.name.innerHTML = data[ele]['name']

        res_list.querySelector('.list-search').innerHTML += copy_srch.outerHTML;
      }

    }
  }
}

