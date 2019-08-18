import React from "react";
import Typing from './Typing';
import "./css/Messages.css";
import avatar from "./img/avatar.png";

 export default ({ chats }) => (
   <div id="chat">
     {chats.map(chat => {
       if(chat.type === 'bot'){
         return (
           <div>
             <img/>
             <div class="bot">{chat.message}</div>
           </div>

         );
       }
       else if(chat.type === 'user'){
         return(
          <div class="user">{chat.message}</div>
         );
       }
       else if(chat.type === 'loading'){
         return(
          <div class="bot"><Typing/></div>
         );
       }
     })}
   </div>
 );
