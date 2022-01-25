var VjsButton = videojs.getComponent('Button');
var zeplayer;
var frametime;
var step_size;

var FBFButton = videojs.extend(VjsButton, {
    constructor: function(player, options) {
        VjsButton.call(this, player, options);
        this.player = player;
        this.frameTime = 1/options.fps;
        this.step_size = options.value;
        frameTime = 1/options.fps;
        step_size = options.value;
        this.on('click', this.onClick);
        this.on('touchstart', this.onClick);      
    },

    onClick: function() {
        // Start by pausing the player
        this.player.pause();
        // Calculate movement distance
        var dist = this.frameTime * this.step_size;
        this.player.currentTime(this.player.currentTime() + dist);
    },
});


$(document).on('keyup', function(e) {
    if (e.keyCode == '37') {
        // Start by pausing the player
        zeplayer.pause();
        // Calculate movement distance
        dist = frameTime * -1;
        zeplayer.currentTime(zeplayer.currentTime() + dist);
     } else if(e.keyCode == '39') {
        // Start by pausing the player
        zeplayer.pause();
        // Calculate movement distance
        dist = frameTime * 1;
        zeplayer.currentTime(zeplayer.currentTime() + dist);
     }
});


function framebyframe(options) {
    zeplayer = this;
    var player = this,
        frameTime = 1 / 30; // assume 30 fps

    player.ready(function() {
        options.steps.forEach(function(opt) {
            
            if(opt.step == "-1") {
                player.controlBar.addChild(
                    new FBFButton(player, {
                        el: videojs.dom.createEl(
                            'button',
                            {
                                className: 'vjs-res-button vjs-control',
                                innerHTML: '<div class="vjs-control-content" style="font-size: 11px;"><span class="vjs-fbf"><img src="https://cdn.agora.community/img/framestep.svg" /></span></div>'
                            },
                            {
                                role: 'button'
                            }
                        ),
                        value: opt.step,
                        fps: options.fps,
                    }),
                {}, opt.index);
            } else {
                player.controlBar.addChild(
                    new FBFButton(player, {
                        el: videojs.dom.createEl(
                            'button',
                            {
                                className: 'vjs-res-button vjs-control',
                                innerHTML: '<div class="vjs-control-content" style="font-size: 11px;"><span class="vjs-fbf"><img style="transform: rotateY(180deg);" src="https://cdn.agora.community/img/framestep.svg" /></span></div>'
                            },
                            {
                                role: 'button'
                            }
                        ),
                        value: opt.step,
                        fps: options.fps,
                    }),
                {}, opt.index);
            }
        });
    });
}
videojs.registerPlugin('framebyframe', framebyframe);