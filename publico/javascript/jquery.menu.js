(function(F){var E=[],L=[],J=activeItem=null,C=F("<div class=\"menu-div outerbox\" style=\"position:absolute;top:0;left:0;display:none;\"><div class=\"shadowbox1\"></div><div class=\"shadowbox2\"></div><div class=\"shadowbox3\"></div></div>")[0],B=F("<ul class=\"menu-ul innerbox\"></ul>")[0],K=F("<li style=\"position:relative;\"><div class=\"menu-item\"  onclick=\"referenciaHijo(this)\" "+"></div></li>")[0],I=F("<img class=\"menu-item-arrow\" />")[0],H=F("<div id=\"root-menu-div\" style=\"position:absolute;top:0;left:0;\"></div>"),D={showDelay:200,hideDelay:200,hoverOpenDelay:0,offsetTop:0,offsetLeft:0,minWidth:0,onOpen:null,onClose:null,onClick:null,arrowSrc:null,addExpando:false,copyClassAttr:false};F(function(){H.appendTo("body")});F.extend({MenuCollection:function(M){this.menus=[];this.init(M)}});F.extend(F.MenuCollection,{prototype:{init:function(M){if(M&&M.length){for(var N=0;N<M.length;N++){this.addMenu(M[N]);M[N].menuCollection=this}}},addMenu:function(N){if(N instanceof F.Menu){this.menus.push(N)}N.menuCollection=this;var M=this;F(N.target).hover(function(){if(N.visible){return }for(var O=0;O<M.menus.length;O++){if(M.menus[O].visible){M.menus[O].hide();N.show();return }}},function(){})}}});F.extend({Menu:function(O,M,N){this.menuItems=[];this.subMenus=[];this.visible=false;this.active=false;this.parentMenuItem=null;this.settings=F.extend({},D,N);this.target=O;this.$eDIV=null;this.$eUL=null;this.timer=null;this.menuCollection=null;this.openTimer=null;this.init();if(M&&M.constructor==Array){this.addItems(M)}}});F.extend(F.Menu,{checkMouse:function(N){var M=N.target;if(L.length&&M==L[0].target){return }while(M.parentNode&&M.parentNode!=H[0]){M=M.parentNode}if(!F(L).filter(function(){return this.$eDIV[0]==M}).length){F.Menu.closeAll()}},checkKey:function(R){switch(R.keyCode){case 13:if(activeItem){activeItem.click(R,activeItem.$eLI[0])}break;case 27:F.Menu.closeAll();break;case 37:if(!J){J=L[0]}var N=J;if(N&&N.parentMenuItem){var P=N.parentMenuItem;P.$eLI.unbind("mouseout").unbind("mouseover");N.hide();P.hoverIn(true);setTimeout(function(){P.bindHover()})}else{if(N&&N.menuCollection){var S,O=N.menuCollection.menus;if((S=F.inArray(N,O))>-1){if(--S<0){S=O.length-1}F.Menu.closeAll();O[S].show();O[S].setActive();if(O[S].menuItems.length){O[S].menuItems[0].hoverIn(true)}}}}break;case 38:if(J){J.selectNextItem(-1)}break;case 39:if(!J){J=L[0]}var M,N=J,Q=activeItem?activeItem.subMenu:null;if(N){if(Q&&Q.menuItems.length){Q.show();Q.menuItems[0].hoverIn()}else{if((N=N.inMenuCollection())){var S,O=N.menuCollection.menus;if((S=F.inArray(N,O))>-1){if(++S>=O.length){S=0}F.Menu.closeAll();O[S].show();O[S].setActive();if(O[S].menuItems.length){O[S].menuItems[0].hoverIn(true)}}}}}break;case 40:if(!J){if(L.length&&L[0].menuItems.length){L[0].menuItems[0].hoverIn()}}else{J.selectNextItem()}break}if(R.keyCode>36&&R.keyCode<41){return false}},closeAll:function(){while(L.length){L[0].hide()}},setDefaults:function(M){F.extend(D,M)},prototype:{init:function(){var M=this;if(!this.target){return }else{if(this.target instanceof F.MenuItem){this.parentMenuItem=this.target;this.target.addSubMenu(this);this.target=this.target.$eLI}}E.push(this);this.$eDIV=F(C.cloneNode(1));this.$eUL=F(B.cloneNode(1));this.$eDIV[0].appendChild(this.$eUL[0]);H[0].appendChild(this.$eDIV[0]);if(!this.parentMenuItem){F(this.target).click(function(N){M.onClick(N)}).hover(function(N){M.setActive();if(M.settings.hoverOpenDelay){M.openTimer=setTimeout(function(){if(!M.visible){M.onClick(N)}},M.settings.hoverOpenDelay)}},function(){if(!M.visible){F(this).removeClass("activetarget")}if(M.openTimer){clearTimeout(M.openTimer)}})}else{this.$eDIV.hover(function(){M.setActive()},function(){})}},setActive:function(){if(!this.parentMenuItem){F(this.target).addClass("activetarget")}else{this.active=true}},addItem:function(M){if(M instanceof F.MenuItem){if(F.inArray(M,this.menuItems)==-1){this.$eUL.append(M.$eLI);this.menuItems.push(M);M.parentMenu=this;if(M.subMenu){this.subMenus.push(M.subMenu)}}}else{this.addItem(new F.MenuItem(M,this.settings))}},addItems:function(M){for(var N=0;N<M.length;N++){this.addItem(M[N])}},removeItem:function(M){var N=F.inArray(M,this.menuItems);if(N>-1){this.menuItems.splice(N,1)}M.parentMenu=null},hide:function(){if(!this.visible){return }var M,N=F.inArray(this,L);this.$eDIV.hide();if(N>=0){L.splice(N,1)}this.visible=this.active=false;F(this.target).removeClass("activetarget");for(M=0;M<this.subMenus.length;M++){this.subMenus[M].hide()}for(M=0;M<this.menuItems.length;M++){if(this.menuItems[M].active){this.menuItems[M].setInactive()}}if(!L.length){F(document).unbind("mousedown",F.Menu.checkMouse).unbind("keydown",F.Menu.checkKey)}if(J==this){J=null}if(this.settings.onClose){this.settings.onClose.call(this)}},show:function(O){if(this.visible){return }var N,M=this.parentMenuItem;if(this.menuItems.length){if(M){N=parseInt(M.parentMenu.$eDIV.css("z-index"));this.$eDIV.css("z-index",(isNaN(N)?1:N+1))}this.$eDIV.css({visibility:"hidden",display:"block"});if(this.settings.minWidth){if(this.$eDIV.width()<this.settings.minWidth){this.$eDIV.css("width",this.settings.minWidth)}}this.setPosition();this.$eDIV.css({display:"none",visibility:""}).show();if(F.browser.msie){this.$eUL.css("width",parseInt(F.browser.version)==6?this.$eDIV.width()-7:this.$eUL.width())}if(this.settings.onOpen){this.settings.onOpen.call(this)}}if(L.length==0){F(document).bind("mousedown",F.Menu.checkMouse).bind("keydown",F.Menu.checkKey)}this.visible=true;L.push(this)},setPosition:function(){var S,Q,O,M,V,W,R,T=F(window).width(),N=F(window).height(),X=this.parentMenuItem,Y=this.$eDIV[0].clientHeight,P=this.$eDIV[0].clientWidth,U;if(X){Q=X.$eLI.offset();O=Q.left+X.$eLI.width();M=Q.top}else{S=F(this.target);Q=S.offset();O=Q.left+this.settings.offsetLeft;M=Q.top+S.height()+this.settings.offsetTop}if(F.fn.scrollTop){W=F(window).scrollTop();if(N<Y){M=W}else{if(N+W<M+Y){if(X){V=X.parentMenu.$eDIV.offset();U=X.parentMenu.$eDIV[0].clientHeight;if(Y<=U){M=V.top+U-Y}else{M=V.top}if(N+W<M+Y){M-=M+Y-(N+W)}}else{M-=M+Y-(N+W)}}}}if(F.fn.scrollLeft){R=F(window).scrollLeft();if(T+R<O+P){if(X){O-=X.$eLI.width()+P;if(O<R){O=R}}else{O-=O+P-(T+R)}}}this.$eDIV.css({left:O,top:M})},onClick:function(M){if(this.visible){this.hide();this.setActive()}else{F.Menu.closeAll();this.show(M)}},addTimer:function(O,N){var M=this;this.timer=setTimeout(function(){O.call(M);M.timer=null},N)},removeTimer:function(){if(this.timer){clearTimeout(this.timer);this.timer=null}},selectNextItem:function(P){var M,Q=0,N=this.menuItems.length,O=P||1;for(M=0;M<N;M++){if(this.menuItems[M].active){Q=M;break}}this.menuItems[Q].hoverOut();do{Q+=O;if(Q>=N){Q=0}else{if(Q<0){Q=N-1}}}while(this.menuItems[Q].separator);this.menuItems[Q].hoverIn(true)},inMenuCollection:function(){var M=this;while(M.parentMenuItem){M=M.parentMenuItem.parentMenu}return M.menuCollection?M:null},destroy:function(){var N,M;this.hide();if(!this.parentMenuItem){F(this.target).unbind("click").unbind("mouseover").unbind("mouseout")}else{this.$eDIV.unbind("mouseover").unbind("mouseout")}while(this.menuItems.length){M=this.menuItems[0];M.destroy();delete M}if((N=F.inArray(this,E))>-1){E.splice(N,1)}if(this.menuCollection){if((N=F.inArray(this,this.menuCollection.menus))>-1){this.menuCollection.menus.splice(N,1)}}this.$eDIV.remove()}}});F.extend({MenuItem:function(N,M){if(typeof N=="string"){N={src:N}}this.src=N.src||"";this.url=N.url||null;this.urlTarget=N.target||null;this.addClass=N.addClass||null;this.data=N.data||null;this.$eLI=null;this.parentMenu=null;this.subMenu=null;this.settings=F.extend({},D,M);this.active=false;this.enabled=true;this.separator=false;this.init();if(N.subMenu){new F.Menu(this,N.subMenu,M)}}});F.extend(F.MenuItem,{prototype:{init:function(){var O,N,P=this.src,M=this;this.$eLI=F(K.cloneNode(1));if(this.addClass){this.$eLI[0].setAttribute("class",this.addClass)}if(this.settings.addExpando&&this.data){this.$eLI[0].menuData=this.data}if(P==""){this.$eLI.addClass("menu-separator");this.separator=true}else{N=typeof P=="string";if(N&&this.url){P=F("<a href=\""+this.url+"\""+(this.urlTarget?"target=\""+this.urlTarget+"\"":"")+">"+P+"</a>")}else{if(N||!P.length){P=[P]}}for(O=0;O<P.length;O++){if(typeof P[O]=="string"){elem=document.createElement("span");elem.innerHTML=P[O];this.$eLI[0].firstChild.appendChild(elem)}else{this.$eLI[0].firstChild.appendChild(P[O].cloneNode(1))}}}this.$eLI.click(function(Q){M.click(Q,this)});this.bindHover()},click:function(N,M){if(this.enabled&&this.settings.onClick){this.settings.onClick.call(M,N,this)}},bindHover:function(){var M=this;this.$eLI.hover(function(){M.hoverIn()},function(){M.hoverOut()})},hoverIn:function(N){this.removeTimer();var O,Q=this.parentMenu.subMenus,P=this.parentMenu.menuItems,M=this;if(this.parentMenu.timer){this.parentMenu.removeTimer()}if(!this.enabled){return }for(O=0;O<P.length;O++){if(P[O].active){P[O].setInactive()}}this.setActive();J=this.parentMenu;for(O=0;O<Q.length;O++){if(Q[O].visible&&Q[O]!=this.subMenu&&!Q[O].timer){Q[O].addTimer(function(){this.hide()},Q[O].settings.hideDelay)}}if(this.subMenu&&!N){this.subMenu.addTimer(function(){this.show()},this.subMenu.settings.showDelay)}},hoverOut:function(){this.removeTimer();if(!this.enabled){return }if(!this.subMenu||!this.subMenu.visible){this.setInactive()}},removeTimer:function(){if(this.subMenu){this.subMenu.removeTimer()}},setActive:function(){this.active=true;this.$eLI.addClass("active");var M=this.parentMenu.parentMenuItem;if(M&&!M.active){M.setActive()}activeItem=this},setInactive:function(){this.active=false;this.$eLI.removeClass("active");if(this==activeItem){activeItem=null}},enable:function(){this.$eLI.removeClass("disabled");this.enabled=true},disable:function(){this.$eLI.addClass("disabled");this.enabled=false},destroy:function(){this.removeTimer();this.$eLI.remove();this.$eLI.unbind("mouseover").unbind("mouseout").unbind("click");if(this.subMenu){this.subMenu.destroy();delete this.subMenu}this.parentMenu.removeItem(this)},addSubMenu:function(N){if(this.subMenu){return }this.subMenu=N;if(this.parentMenu&&F.inArray(N,this.parentMenu.subMenus)==-1){this.parentMenu.subMenus.push(N)}if(this.settings.arrowSrc){var M=I.cloneNode(0);M.setAttribute("src",this.settings.arrowSrc);this.$eLI[0].firstChild.appendChild(M)}}}});F.extend(F.fn,{menuFromElement:function(N,P,O){var M=function(X){var T=[],V,S,a,Y,U,Z,R,W,Q=null;a=G(X,"LI");for(U=0;U<a.length;U++){V=[];if(!a[U].childNodes.length){T.push(new F.MenuItem("",N));continue}if((Z=A(a[U],"UL"))){V=M(Z);F(Z).remove()}Y=F(a[U]);if(Y[0].childNodes.length==1&&Y[0].childNodes[0].nodeType==3){W=Y[0].childNodes[0].nodeValue}else{W=Y[0].childNodes}if(N&&N.copyClassAttr){Q=Y.attr("class")}S=new F.MenuItem({src:W,addClass:Q},N);T.push(S);if(V.length){new F.Menu(S,V,N)}}return T};return this.each(function(){var R,Q;if(P||(R=A(this,"UL"))){R=P?F(P).clone(true)[0]:R;menuItems=M(R);if(menuItems.length){Q=new F.Menu(this,menuItems,N);if(O){O.addMenu(Q)}}F(R).hide()}})},menuBarFromUL:function(M){return this.each(function(){var O,N=G(this,"LI");if(N.length){bar=new F.MenuCollection();for(O=0;O<N.length;O++){F(N[O]).menuFromElement(M,null,bar)}}})},menu:function(N,M){return this.each(function(){if(M&&M.constructor==Array){new F.Menu(this,M,N)}else{if(this.nodeName.toUpperCase()=="UL"){F(this).menuBarFromUL(N)}else{F(this).menuFromElement(N,M)}}})}});var A=function(N,M){if(!N){return null}var O=N.firstChild;for(;O;O=O.nextSibling){if(O.nodeType==1&&O.nodeName.toUpperCase()==M){return O}}return null};var G=function(O,M){if(!O){return[]}var N=[],P=O.firstChild;for(;P;P=P.nextSibling){if(P.nodeType==1&&P.nodeName.toUpperCase()==M){N[N.length]=P}}return N}})(jQuery);
