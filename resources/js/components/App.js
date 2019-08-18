import React, { Component } from 'react'
import ReactDOM from 'react-dom'
import Input from './Input'
import Messages from './Messages'
import Echo from "laravel-echo"
import Pusher from 'pusher-js';

class App extends Component {
  constructor(props) {
    super(props);
    this.state = {
      text: '',
      username: '',
      chats: []
    };
  }

  componentDidMount() {
    const username = "Dan"
    this.setState({ username });
    const payload = {
      name: this.state.username,
    };
    axios.get('/messages',payload).then((response)=>{
      console.log(response);
    })
    .catch((error)=>{
      console.log(error);
    });

    const pusher = new Pusher('cb265c4b7d6b311c2fd8', {
      cluster: 'eu',
      encrypted: true
    });
    const channel = pusher.subscribe('chat');
    channel.bind('message', data => {
      setTimeout(()=>{
        this.setState({ chats: [...this.state.chats, data], test: '' });
        console.log(this.state.chats);
        window.scrollTo(0,document.body.scrollHeight);
      }, 200);
    });
    this.handleTextChange = this.handleTextChange.bind(this);
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
    } else {
      //update state on changed text box
      this.setState({ text: e.target.value });
    }
  }

  render() {

    return (
      <div className="App">
        <Messages chats={this.state.chats}/>
        <Input
          text={this.state.text}
          handleTextChange={this.handleTextChange}
        />
      </div>
    );
  }
}

export default App;
