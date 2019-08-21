import React from "react";
import Typing from './Typing';
import "./css/Messages.css";
import avatar from "./img/avatar.png";

let style= {

}

 export default ({ chats,loading }) => (
   <div id="chat">
     {chats.map(chat => {
       if(chat.type === 'bot'){
         return (
           <div className="bot-message">
              <img src={avatar}/>
              <div class="bot">{chat.message}</div>
           </div>

         );
       }
       else if(chat.type === 'user'){
         return(
          <div class="user">{chat.message}</div>
         );
       }
     })}
     { loading ? (
       <div className="bot-message">
          <img src={avatar}/>
          <div class="bot"><Typing/></div>
       </div>
     ) : "" }
   </div>
 );
