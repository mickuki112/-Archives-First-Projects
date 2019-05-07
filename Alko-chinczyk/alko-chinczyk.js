	var nuberOfPlayers = 2;
	var whoPlays = 0;
	var placeOfPlayers = [0,0,0,0,0];
	var placeOfPlayersColor = ['Czarnego','Czerwonego',"Niebieskiego","Żułtego","Zielonego"];
	var lock=false
function showPawn (numbergo, time, lastPlace){
	var content = $('#field'+numbergo).html();
	$.scrollTo($('#field'+numbergo), 500);
	$('#field'+numbergo).html("<img src='pawn" + whoPlays + ".png' class=xx"+whoPlays+">"+content); //linia odpowiedzilna za pokazywanie sie pionka
	if((numbergo == lastPlace-1 && placeOfPlayers[whoPlays] != numbergo) || lastPlace-placeOfPlayers[whoPlays] == 1 )
		{}
	else
		{setTimeout(function(){hidePawn(numbergo, content); }, 600);}
}
function hidePawn(numbergo, content){
		$('#field' + numbergo).html(content);//linia odpowiedzilna za znikanie pionka
}
function firstMotion(numbergo) {
	var content = $('#field'+(numbergo-1)).html();
	var textChanged = content.replace("xx"+whoPlays,'yyy');
	$('#field' + (numbergo-1)).html(textChanged);
	}
function kulniecie(){
	if(lock==false){
	lock=true;
	firstMotion (placeOfPlayers[whoPlays]);
	var numberThrownOut = Math.floor(Math.random() * 6)+1;
	$("#cubeResult").html("Wykulłeś: "+numberThrownOut);
	for(let i=0;i<numberThrownOut;i++){
		setTimeout(function(){showPawn(i + placeOfPlayers[whoPlays], i*600, placeOfPlayers[whoPlays]+numberThrownOut); },i*600);
		}
	setTimeout(function() {
		startPause(numberThrownOut, placeOfPlayersColor[whoPlays]);
		placeOfPlayers[whoPlays] = placeOfPlayers[whoPlays] + numberThrownOut;
		theEnd();
		if(nuberOfPlayers-1==whoPlays)
			{whoPlays=0;}
		
		else
			{whoPlays++}
		lock=false
	},numberThrownOut*600)}

	
}
function startPause(numberThrownOut,who){
	$(".menu").html('<div class="col-sm-12"><input id="Nastepny" onclick="stopPause()" type="submit" value="Nastepny"></div><div class="col-sm-12" id="cubeResult">Wykulłeś:'+numberThrownOut+'</div><div class="col-sm-12" id="player">Koniec ruch gracza: '+who+'</div>');	
	}
function stopPause(){
	$.scrollTo($('#field'+ (placeOfPlayers[whoPlays]-1)), 500);
	$(".menu").html('<div class="col-sm-12"><input id="Kulni" onclick="kulniecie()" type="submit" value="kulni"></div><div class="col-sm-12" id="cubeResult">Wykulłeś: </div><div class="col-sm-12" id="player">Ruch gracza: '+placeOfPlayersColor[whoPlays]+'"</div>');	
	}
function startgame(){
	nuberOfPlayers = document.getElementById("numberPlay").value;
	if(nuberOfPlayers>1 && nuberOfPlayers<6)
	{
		stopPause();
	}
	else
	{
		alert("Niedozwolony przedział");
		return;
	}
	}
function theEnd(){
	for(var i=0;i<4;i++)
	{
		if(placeOfPlayers[i]>38)
		{
			$(".menu").html("<div class='winner'>Wygrywa gracz koloru: "+placeOfPlayersColor[i]+"</div>");
			
		}
	}
}

$('#start').on('click', function(){startgame()})
	