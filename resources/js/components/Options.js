import React, { Component } from 'react'
import './css/Options.css';

export default ({ options,handleOptionClick }) => (
  <div id="options">
  {options.map((option,i) => {
      return (
        <div key={i} onClick={handleOptionClick}>{option}</div>
      );
  })}
  </div>
);
