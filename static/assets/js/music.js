  var current = 0
        var musicFlag=false
        var musicImg=document.getElementById('music-img')
        var mp3=document.getElementById('bgMusic')
        musicImg.onclick = function(){
                if(musicFlag){
                    clearInterval(timeId)
                    musicFlag=false
                    mp3.pause()
                    return
                }
             timeId=setInterval(function () {
                current = (current+1)%360;
                musicImg.style.transform = 'rotate('+current+'deg)';
                mp3.play()
                 musicFlag=true
            },50)

        }