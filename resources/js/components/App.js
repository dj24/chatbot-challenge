import React, { Component } from 'react'
import ReactDOM from 'react-dom'
import Input from './Input'
import Messages from './Messages'
import Options from './Options'
import Echo from "laravel-echo"
import Pusher from 'pusher-js';

class App extends Component {
  constructor(props) {
    super(props);
    this.state = {
      text: '',
      username: '',
      chats: [],
      options: []
    };
  }

  setupPusher(){
    const pusher = new Pusher('cb265c4b7d6b311c2fd8', {
      cluster: 'eu',
      encrypted: true
    });
    const channel = pusher.subscribe('chat');
    channel.bind('message', data => {
      setTimeout(()=>{
        this.setState({ chats: [...this.state.chats, data], test: '' });
        window.scrollTo(0,document.body.scrollHeight);
      }, 200);
    });
    channel.bind('options', data => {
        this.setState({ options: data.options});
        console.log(this.state.options[0]);
    });
  }

  componentDidMount() {
    this.setupPusher();

    var username= prompt("Please enter your username",
                "Dan");
    this.setState({ username });
    axios.get('/messages/' + username).then((response)=>{
      let chats = response.data;
      this.setState({chats});
    })
    .catch((error)=>{
      console.log(error);
    });


    this.handleTextChange = this.handleTextChange.bind(this);
    this.handleOptionClick = this.handleOptionClick.bind(this);
  }

  handleOptionClick(e) {
    let message = e.target.innerText;
    console.log(message);
    const payload = {
      name: this.state.username,
      message
    };
    this.setState({text: ''});
    axios.post('/message', payload).then((response)=>{
      const data = {
        message: payload.message,
        type: 'user',
      };
      this.setState({ chats: [...this.state.chats, data], test: '' });
      this.setState({ options: []});
      window.scrollTo(0,document.body.scrollHeight);
    })
    .catch((error)=>{
      console.log(error);
    });
  }

  handleTextChange(e) {
    if (e.keyCode === 13) {
      //enter pressed
      const payload = {
        name: this.state.username,
        message: this.state.text
      };
      this.setState({text: ''});
      axios.post('/message', payload).then((response)=>{
        const data = {
          message: payload.message,
          type: 'user',
        };
        this.setState({ chats: [...this.state.chats, data], test: '' });
        window.scrollTo(0,document.body.scrollHeight);
      })
      .catch((error)=>{
        console.log(error);
      });
      this.setState({ options: []});
    } else {
      //update state on changed text box
      this.setState({ text: e.target.value });
    }
  }

  render() {

    return (
      <div className="App">
        <Messages chats={this.state.chats}/>
        <Options
          handleOptionClick={this.handleOptionClick}
          options={this.state.options}
        />
        <Input
          text={this.state.text}
          handleTextChange={this.handleTextChange}
        />
      </div>
    );
  }
}

export default App;
