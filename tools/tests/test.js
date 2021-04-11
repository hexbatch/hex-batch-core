let sendMail = new SendMail();
sendMail.setTo("foo@example.org");

let otherMail = new SendMail();
otherMail.setTo("bar@example.org");

sendMail.send();
otherMail.send();
let me = sendMail.get_me();
print("got me, its " + me);
let json = sendMail.get_json();
let nu = JSON.parse(json);
for(let i in nu) {
    if (!nu.hasOwnProperty(i)) {continue;}
    print("nu has " + i + " => " + nu[i]);
}