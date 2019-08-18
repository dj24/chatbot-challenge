import React from "react";
import "./css/Messages.css";
import avatar from "./img/avatar.png";

 export default ({ chats }) => (
   <div id="chat">
     {chats.map(chat => {
       if(chat.type === 'bot'){
         return (
           <div class="bot">{chat.message}</div>
         );
       }
       else if(chat.type === 'user'){
         return(
          <div class="user">{chat.message}</div>
         );
       }
     })}
   </div>
 );
