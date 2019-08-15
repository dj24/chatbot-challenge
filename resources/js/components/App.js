import React, { Component } from 'react'
import ReactDOM from 'react-dom'
import Input from './Input'
import Messages from './Messages'
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
    const pusher = new Pusher('cb265c4b7d6b311c2fd8', {
      cluster: 'eu',
      encrypted: true
    });
    const channel = pusher.subscribe('chat');
    channel.bind('message', data => {
      this.setState({ chats: [...this.state.chats, data], test: '' });
    });
    this.handleTextChange = this.handleTextChange.bind(this);
    console.log(this.state);
  }

  handleTextChange(e) {
    if (e.keyCode === 13) {
      const payload = {
        username: this.state.username,
        message: this.state.text
      };
      axios.post('http://localhost:8000/message', payload);
    } else {
      this.setState({ text: e.target.value });
    }
  }

  render() {
    return (
      <div className="App">
        <header className="App-header">
          <h1 className="App-title">Welcome to React-Pusher Chat</h1>
        </header>
        <section>
        <Input
          text={this.state.text}
          username={this.state.username}
          handleTextChange={this.handleTextChange}
        />
        </section>
      </div>
    );
  }
}

export default App;
