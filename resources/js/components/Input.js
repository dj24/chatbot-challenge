import React, { Component } from 'react'
import './css/Input.css';

export default ({ text,handleTextChange }) => (
  <div class="input-container">
    <input
      type="text"
      value={text}
      placeholder="chat here..."
      className="form-control chat-input"
      onChange={handleTextChange}
      onKeyDown={handleTextChange}
    />
  </div>
);
