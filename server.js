var renderAsync = require('render-async'),
jQuery = require('js-toolbox')._jQuery;

//now we need a server for this so that we can test include
var app= renderAsync.express({welcomeFile: "index.js.html"});

app.set('views', __dirname + '/public');

//server everything js.html
app.get("*.js.html", renderAsync.renderAsync);

//server everything else
app.use(renderAsync.webServer);


//set ipaddress from openshift, to command line or to localhost:8080
var ipaddr = process.env.OPENSHIFT_NODEJS_IP || "127.0.0.1";
var port = process.env.OPENSHIFT_NODEJS_PORT || parseInt(process.argv.pop()) || 8080;
app.set('port', port);
//start the server listening for requests
app.listen(port, ipaddr);
