"use strict";function _interopRequireDefault(e){return e&&e.__esModule?e:{default:e}}function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}var _createClass=function(){function e(e,t){for(var a=0;a<t.length;a++){var r=t[a];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}return function(t,a,r){return a&&e(t.prototype,a),r&&e(t,r),t}}(),_react=require("react"),_react2=_interopRequireDefault(_react);require("./Authorize.css");var _http=require("rufUtils/http"),_http2=_interopRequireDefault(_http),Authorize=function(e){function t(){_classCallCheck(this,t);var e=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this));return e.state={client:null,scopes:[]},e.declined=e.declined.bind(e),e}return _inherits(t,e),_createClass(t,[{key:"componentWillUnmount",value:function(){this.unmounted=!0}},{key:"componentDidMount",value:function(){var e=this;_http2.default.post("/oauth/authorizeApi",{client_id:this.props.location.query.client_id,scope:this.props.location.query.scope,redirect_uri:this.props.location.query.redirect_uri,response_type:this.props.location.query.response_type}).then(function(t){e.unmounted||e.setState({client:t.client,scopes:t.scopes})})}},{key:"declined",value:function(){document.location=this.props.location.query.redirect_uri+"?declined=1"}},{key:"render",value:function(){return this.state.client?_react2.default.createElement("div",{className:"container authorize"},_react2.default.createElement("div",{className:"row"},_react2.default.createElement("div",{className:"col-md-6 ml-auto mr-auto"},_react2.default.createElement("div",{className:"card"},_react2.default.createElement("div",{className:"card-header"},"Authorization Request"),_react2.default.createElement("div",{className:"card-body"},_react2.default.createElement("p",null,_react2.default.createElement("strong",null,this.state.client.name)," is requesting permission to access your account."),this.state.scopes.length>0&&_react2.default.createElement("div",{className:"scopes"},_react2.default.createElement("p",null,_react2.default.createElement("strong",null,"This application will have access to:")),_react2.default.createElement("ul",null,this.state.scopes.map(function(e){return _react2.default.createElement("li",{key:e.id},e.description)}))),_react2.default.createElement("div",{className:"buttons"},_react2.default.createElement("form",{method:"get",action:"/oauth/authorize"},_react2.default.createElement("input",{type:"hidden",name:"response_type",value:"code"}),_react2.default.createElement("input",{type:"hidden",name:"client_id",value:this.state.client.id}),_react2.default.createElement("input",{type:"hidden",name:"redirect_uri",value:this.props.location.query.redirect_uri}),_react2.default.createElement("input",{type:"hidden",name:"scope",value:this.props.location.query.scope.split(",").join(" ")}),_react2.default.createElement("input",{type:"hidden",name:"grant_type",value:"authorization_code"}),_react2.default.createElement("button",{type:"submit",className:"btn btn-success btn-approve"},"Authorize")),_react2.default.createElement("button",{className:"btn btn-danger",onClick:this.declined},"Cancel"))))))):_react2.default.createElement("div",{className:"text-center"},_react2.default.createElement("div",{className:"fa fa-3x fa-cog fa-spin"}))}}]),t}(_react.Component);module.exports=Authorize;