import React, { Component } from 'react'
import ReactDOM from 'react-dom'
import Input from './Input'
import Messages from './Messages'
import Options from './Options'
import Typing from './Typing'
import Echo from "laravel-echo"
import Pusher from 'pusher-js';

class App extends Component {
  constructor(props) {
    super(props);
    this.state = {
      text: '',
      username: '',
      chats: [{"type" : "loading"}],
      options: [],
      loading: true
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
        this.setState({ options: []});
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
      console.log("done loading");
      this.setState({loading:false});
    })
    .catch((error)=>{
      console.log(error);
    });
    this.handleTextChange = this.handleTextChange.bind(this);
    this.handleOptionClick = this.handleOptionClick.bind(this);
  }

  handleOptionClick(e) {
    let message = e.target.innerText;
    let options = this.state.options;
    console.log(message);
    const payload = {
      name: this.state.username,
      message
    };
    const newChat = {
      message: payload.message,
      type: 'user',
    };
    this.setState({text: ''});
    this.setState({loading:true});
    this.setState({ chats: [...this.state.chats, newChat]});
    axios.post('/message', payload).then((response)=>{
      this.setState({loading:false});
      if(this.state.options === options){
        this.setState({ options: []});
      }
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
      const newChat = {
        message: payload.message,
        type: 'user',
      };
      this.setState({text: ''});
      this.setState({loading:true});
      this.setState({ chats: [...this.state.chats, newChat]});
      axios.post('/message', payload).then((response)=>{
        this.setState({loading:false});
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
        <Messages
          loading={this.state.loading}
          chats={this.state.chats}
        />
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
