onload = function(){
    var example = document.getElementById("example"),
	ctx = example.getContext("2d");
	ctx.onclick = function(){alert();}
    ctx.arc(10,17, 3, (Math.PI/180)*0 , (Math.PI/180)*360);
    ctx.fillStyle = 'red';
	ctx.fill();

}