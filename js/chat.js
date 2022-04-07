// const for user header
const user_img = document.querySelector('.user-row img'),
      user_name = document.querySelector('.user-row .name'),
      user_stat = document.querySelector('.user-row .status'),
      user_stat_dot = document.querySelector('.user-row .status-dot');
  
const inp_msg = document.querySelector(".typing .msg"),
      btn_msg = document.querySelector(".typing .send-btn")

const copy_message = document.querySelector('.chat-box .copy'),
      box_chat = document.querySelector('.chat-box'),
      box_all = document.querySelector('.chat-area')

const date_chat = document.querySelector('.chat-box .date')

let info = {
  send_id: inp_msg.dataset.id_send,
  rec_id: inp_msg.dataset.id_rec,
  last_time: 0,
  last_user: 0
}

function getInfo(){
  let xml=new XMLHttpRequest();
  xml.onreadystatechange=function() {
      if (this.readyState==4 && this.status==200) {
        console.log(this.responseText)
        resp = JSON.parse(this.responseText);
        setInfo(resp);
        getMessage();
      }
  }
  xml.open("GET",'connect/chat.php?getInfo='+info.rec_id,true);
  xml.send();
}
getInfo();

function setInfo(data, stat = 0){

  if(stat == 0){
    user_name.innerHTML = data['name'];
    user_img.src = "images/" + data['img'];
  }

  if(data['status'] == ('online' || 'active' || 1)){
    user_stat.innerHTML = "online";
    user_stat_dot.classList.add('active')
  
  }else{
    user_stat.innerHTML = "offline";
    user_stat_dot.classList.remove('active')
  }
}


console.log(info)

// get Message from DB
function getMessage(){
  let xml=new XMLHttpRequest();
  xml.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200){
      resp = JSON.parse(this.responseText);
      console.log(resp);
      showMessage(resp);
      
    }
  }
  xml.open("POST",'connect/chat.php?showMessage',true);
  xml.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xml.send('send='+info.send_id+"&rec="+info.rec_id);
}


let copy_msg = copy_message.cloneNode(true);
  copy_msg.classList.remove('copy')
  detail = {
    det:  copy_msg.querySelector('.details'),
    msg:  copy_msg.querySelector('.message'),
    date: copy_msg.querySelector('.date'),
    img:  copy_msg.querySelector('img')
  }

// show Message to user
function showMessage(data){
  let from, to, date, msg;
  if(data.status == 1){

    for(elem of data.message){        // loop in message array
      

      if(!(info.last_time >= elem.date)){      // check if new message date same as old or not

        let scrolling
        if(box_chat.scrollTop + box_chat.clientHeight + 40 > box_chat.scrollHeight){
          scrolling = 1;
        }else{ scrolling = 0}

        info.last_time = elem.date;       // set o
        if(elem.send_msg_id == info.send_id){
          copy_msg.classList.add('send')
          copy_msg.classList.remove('rec')

        }else if(elem.rec_msg_id == info.send_id){
          copy_msg.classList.add('rec')
          copy_msg.classList.remove('send')
          //detail.img.src = user_img.src
        }

        if(info.last_user == elem.send_msg_id){

          detail.msg.innerHTML = elem.msg
          detail.date.innerHTML = elem.date
          box_chat.lastElementChild.innerHTML += detail.det.outerHTML

        }else{
          info.last_user = elem.send_msg_id;
          detail.msg.innerHTML = elem.msg
          detail.date.innerHTML = elem.date
          box_chat.innerHTML += copy_msg.outerHTML

        }
        if(scrolling == 1 ){scroll_btm()}
      }
    }

  }else{
    console.log(data.msgStatus)
  }
}

function scroll_btm(){
  box_chat.scrollTop = box_chat.scrollHeight;
}

//receve message
function getLast(){
  let xml=new XMLHttpRequest();
  xml.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
     
      resp = JSON.parse(this.responseText);
      
      if(resp.status == 1){
        console.log(resp)
        showMessage(resp)
      }
    }
  }
  xml.open("POST",'connect/chat.php?getLastMessage',true);
  xml.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xml.send('send='+info.send_id+"&rec="+info.rec_id+"&time="+info.last_time);
}
setInterval(getLast ,1300)


//send message 
btn_msg.onclick = send_msg;
inp_msg.onkeydown = function(e){e.keyCode == 13 ? send_msg() : '';}

function send_msg(){
  let msg = inp_msg.value;
  inp_msg.value = '';
  inp_msg.focus();
  console.log(msg)

  if(msg != ''){
    info.message = msg
  
    let xml=new XMLHttpRequest();
    xml.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
          console.log(this.responseText)
          getLast();
          //resp = JSON.parse(this.responseText);
          
        }
    }
    xml.open("POST",'connect/chat.php?sendMessage=',true);
    xml.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xml.send('send='+JSON.stringify(info));
  }
}



// show date of message
box_chat.onclick = function(e){

  if(e.target.classList.contains('message')){
    e.target.parentElement.querySelector('.date').classList.toggle('show')
    
  }
}

window.onresize = function(){
  let height = document.documentElement.clientHeight;
  box_all.style.height = height+"px";

  if(document.body.clientHeight < 750){
    document.body.style.height = height+"px";
  }else{
    document.body.style.height = '';
  }
}