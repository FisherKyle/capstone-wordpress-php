var SF_DEBUG = true;

;(function (window) {
    var transitions = {
            'transition': 'transitionend',
            'WebkitTransition': 'webkitTransitionEnd',
            'MozTransition': 'transitionend',
            'OTransition': 'otransitionend'
        },
        elem = document.createElement('div');

    for (var t in transitions) {
        if (typeof elem.style[t] !== 'undefined') {
            window.transitionEnd = transitions[t];
            break;
        }
    }
    if (!window.transitionEnd) window.transitionEnd = false;
})(window);

/*! jquery.finger - v0.1.4 - 2015-12-02
 * https://github.com/ngryman/jquery.finger
 * Copyright (c) 2015 Nicolas Gryman; Licensed MIT */
!function (a) {
    "function" == typeof define && define.amd ? define(["jquery"], a) : a("object" == typeof exports ? require("jquery") : jQuery)
}(function (a) {
    function b(c) {
        c.preventDefault(), a.event.remove(v, "click", b)
    }

    function c(a, b) {
        return (q ? b.originalEvent.touches[0] : b)["page" + a.toUpperCase()]
    }

    function d(c, d, e) {
        var h = a.Event(d, x);
        a.event.trigger(h, {originalEvent: c}, c.target), h.isDefaultPrevented() && (~d.indexOf("tap") && !q ? a.event.add(v, "click", b) : c.preventDefault()), e && (a.event.remove(v, t + "." + u, f), a.event.remove(v, s + "." + u, g))
    }

    function e(e) {
        var l = e.timeStamp || +new Date;
        j != l && (j = l, w.x = x.x = c("x", e), w.y = x.y = c("y", e), w.time = l, w.target = e.target, x.orientation = null, x.end = !1, h = !1, i = !1, k = setTimeout(function () {
            i = !0, d(e, "press")
        }, y.pressDuration), a.event.add(v, t + "." + u, f), a.event.add(v, s + "." + u, g), y.preventDefault && (e.preventDefault(), a.event.add(v, "click", b)))
    }

    function f(b) {
        if (x.x = c("x", b), x.y = c("y", b), x.dx = x.x - w.x, x.dy = x.y - w.y, x.adx = Math.abs(x.dx), x.ady = Math.abs(x.dy), h = x.adx > y.motionThreshold || x.ady > y.motionThreshold) {
            for (clearTimeout(k), x.orientation || (x.adx > x.ady ? (x.orientation = "horizontal", x.direction = x.dx > 0 ? 1 : -1) : (x.orientation = "vertical", x.direction = x.dy > 0 ? 1 : -1)); b.target && b.target !== w.target;)b.target = b.target.parentNode;
            return b.target !== w.target ? (b.target = w.target, void g.call(this, a.Event(s + "." + u, b))) : void d(b, "drag")
        }
    }

    function g(a) {
        var b, c = a.timeStamp || +new Date, e = c - w.time;
        if (clearTimeout(k), h || i || a.target !== w.target)a.target = w.target, e < y.flickDuration && d(a, "flick"), x.end = !0, b = "drag"; else {
            var f = l === a.target && c - m < y.doubleTapInterval;
            b = f ? "doubletap" : "tap", l = f ? null : w.target, m = c
        }
        d(a, b, !0)
    }

    var h, i, j, k, l, m, n = navigator.userAgent, o = /chrome/i.exec(n), p = /android/i.exec(n), q = "ontouchstart" in window && !(o && !p), r = q ? "touchstart" : "mousedown", s = q ? "touchend touchcancel" : "mouseup mouseleave", t = q ? "touchmove" : "mousemove", u = "finger", v = a("html")[0], w = {}, x = {}, y = a.Finger = {
        pressDuration: 300,
        doubleTapInterval: 300,
        flickDuration: 150,
        motionThreshold: 5
    };
    return a.event.add(v, r + "." + u, e), a.each("tap doubletap press drag flick".split(" "), function (b, c) {
        a.fn[c] = function (a) {
            return a ? this.on(c, a) : this.trigger(c)
        }
    }), y
});

eval(function (p, a, c, k, e, r) {
    e = function (c) {
        return (c < a ? '' : e(parseInt(c / a))) + ((c = c % a) > 35 ? String.fromCharCode(c + 29) : c.toString(36))
    };
    if (!''.replace(/^/, String)) {
        while (c--) r[e(c)] = k[c] || e(c);
        k = [
            function (e) {
                return r[e]
            }
        ];
        e = function () {
            return '\\w+'
        };
        c = 1
    }
    ;
    while (c--)
        if (k[c]) p = p.replace(new RegExp('\\b' + e(c) + '\\b', 'g'), k[c]);
    return p
}('9 17={3i:\'0.1.3\',16:1e-6};l v(){}v.23={e:l(i){8(i<1||i>7.4.q)?w:7.4[i-1]},2R:l(){8 7.4.q},1u:l(){8 F.1x(7.2u(7))},24:l(a){9 n=7.4.q;9 V=a.4||a;o(n!=V.q){8 1L}J{o(F.13(7.4[n-1]-V[n-1])>17.16){8 1L}}H(--n);8 2x},1q:l(){8 v.u(7.4)},1b:l(a){9 b=[];7.28(l(x,i){b.19(a(x,i))});8 v.u(b)},28:l(a){9 n=7.4.q,k=n,i;J{i=k-n;a(7.4[i],i+1)}H(--n)},2q:l(){9 r=7.1u();o(r===0){8 7.1q()}8 7.1b(l(x){8 x/r})},1C:l(a){9 V=a.4||a;9 n=7.4.q,k=n,i;o(n!=V.q){8 w}9 b=0,1D=0,1F=0;7.28(l(x,i){b+=x*V[i-1];1D+=x*x;1F+=V[i-1]*V[i-1]});1D=F.1x(1D);1F=F.1x(1F);o(1D*1F===0){8 w}9 c=b/(1D*1F);o(c<-1){c=-1}o(c>1){c=1}8 F.37(c)},1m:l(a){9 b=7.1C(a);8(b===w)?w:(b<=17.16)},34:l(a){9 b=7.1C(a);8(b===w)?w:(F.13(b-F.1A)<=17.16)},2k:l(a){9 b=7.2u(a);8(b===w)?w:(F.13(b)<=17.16)},2j:l(a){9 V=a.4||a;o(7.4.q!=V.q){8 w}8 7.1b(l(x,i){8 x+V[i-1]})},2C:l(a){9 V=a.4||a;o(7.4.q!=V.q){8 w}8 7.1b(l(x,i){8 x-V[i-1]})},22:l(k){8 7.1b(l(x){8 x*k})},x:l(k){8 7.22(k)},2u:l(a){9 V=a.4||a;9 i,2g=0,n=7.4.q;o(n!=V.q){8 w}J{2g+=7.4[n-1]*V[n-1]}H(--n);8 2g},2f:l(a){9 B=a.4||a;o(7.4.q!=3||B.q!=3){8 w}9 A=7.4;8 v.u([(A[1]*B[2])-(A[2]*B[1]),(A[2]*B[0])-(A[0]*B[2]),(A[0]*B[1])-(A[1]*B[0])])},2A:l(){9 m=0,n=7.4.q,k=n,i;J{i=k-n;o(F.13(7.4[i])>F.13(m)){m=7.4[i]}}H(--n);8 m},2Z:l(x){9 a=w,n=7.4.q,k=n,i;J{i=k-n;o(a===w&&7.4[i]==x){a=i+1}}H(--n);8 a},3g:l(){8 S.2X(7.4)},2d:l(){8 7.1b(l(x){8 F.2d(x)})},2V:l(x){8 7.1b(l(y){8(F.13(y-x)<=17.16)?x:y})},1o:l(a){o(a.K){8 a.1o(7)}9 V=a.4||a;o(V.q!=7.4.q){8 w}9 b=0,2b;7.28(l(x,i){2b=x-V[i-1];b+=2b*2b});8 F.1x(b)},3a:l(a){8 a.1h(7)},2T:l(a){8 a.1h(7)},1V:l(t,a){9 V,R,x,y,z;2S(7.4.q){27 2:V=a.4||a;o(V.q!=2){8 w}R=S.1R(t).4;x=7.4[0]-V[0];y=7.4[1]-V[1];8 v.u([V[0]+R[0][0]*x+R[0][1]*y,V[1]+R[1][0]*x+R[1][1]*y]);1I;27 3:o(!a.U){8 w}9 C=a.1r(7).4;R=S.1R(t,a.U).4;x=7.4[0]-C[0];y=7.4[1]-C[1];z=7.4[2]-C[2];8 v.u([C[0]+R[0][0]*x+R[0][1]*y+R[0][2]*z,C[1]+R[1][0]*x+R[1][1]*y+R[1][2]*z,C[2]+R[2][0]*x+R[2][1]*y+R[2][2]*z]);1I;2P:8 w}},1t:l(a){o(a.K){9 P=7.4.2O();9 C=a.1r(P).4;8 v.u([C[0]+(C[0]-P[0]),C[1]+(C[1]-P[1]),C[2]+(C[2]-(P[2]||0))])}1d{9 Q=a.4||a;o(7.4.q!=Q.q){8 w}8 7.1b(l(x,i){8 Q[i-1]+(Q[i-1]-x)})}},1N:l(){9 V=7.1q();2S(V.4.q){27 3:1I;27 2:V.4.19(0);1I;2P:8 w}8 V},2n:l(){8\'[\'+7.4.2K(\', \')+\']\'},26:l(a){7.4=(a.4||a).2O();8 7}};v.u=l(a){9 V=25 v();8 V.26(a)};v.i=v.u([1,0,0]);v.j=v.u([0,1,0]);v.k=v.u([0,0,1]);v.2J=l(n){9 a=[];J{a.19(F.2F())}H(--n);8 v.u(a)};v.1j=l(n){9 a=[];J{a.19(0)}H(--n);8 v.u(a)};l S(){}S.23={e:l(i,j){o(i<1||i>7.4.q||j<1||j>7.4[0].q){8 w}8 7.4[i-1][j-1]},33:l(i){o(i>7.4.q){8 w}8 v.u(7.4[i-1])},2E:l(j){o(j>7.4[0].q){8 w}9 a=[],n=7.4.q,k=n,i;J{i=k-n;a.19(7.4[i][j-1])}H(--n);8 v.u(a)},2R:l(){8{2D:7.4.q,1p:7.4[0].q}},2D:l(){8 7.4.q},1p:l(){8 7.4[0].q},24:l(a){9 M=a.4||a;o(1g(M[0][0])==\'1f\'){M=S.u(M).4}o(7.4.q!=M.q||7.4[0].q!=M[0].q){8 1L}9 b=7.4.q,15=b,i,G,10=7.4[0].q,j;J{i=15-b;G=10;J{j=10-G;o(F.13(7.4[i][j]-M[i][j])>17.16){8 1L}}H(--G)}H(--b);8 2x},1q:l(){8 S.u(7.4)},1b:l(a){9 b=[],12=7.4.q,15=12,i,G,10=7.4[0].q,j;J{i=15-12;G=10;b[i]=[];J{j=10-G;b[i][j]=a(7.4[i][j],i+1,j+1)}H(--G)}H(--12);8 S.u(b)},2i:l(a){9 M=a.4||a;o(1g(M[0][0])==\'1f\'){M=S.u(M).4}8(7.4.q==M.q&&7.4[0].q==M[0].q)},2j:l(a){9 M=a.4||a;o(1g(M[0][0])==\'1f\'){M=S.u(M).4}o(!7.2i(M)){8 w}8 7.1b(l(x,i,j){8 x+M[i-1][j-1]})},2C:l(a){9 M=a.4||a;o(1g(M[0][0])==\'1f\'){M=S.u(M).4}o(!7.2i(M)){8 w}8 7.1b(l(x,i,j){8 x-M[i-1][j-1]})},2B:l(a){9 M=a.4||a;o(1g(M[0][0])==\'1f\'){M=S.u(M).4}8(7.4[0].q==M.q)},22:l(a){o(!a.4){8 7.1b(l(x){8 x*a})}9 b=a.1u?2x:1L;9 M=a.4||a;o(1g(M[0][0])==\'1f\'){M=S.u(M).4}o(!7.2B(M)){8 w}9 d=7.4.q,15=d,i,G,10=M[0].q,j;9 e=7.4[0].q,4=[],21,20,c;J{i=15-d;4[i]=[];G=10;J{j=10-G;21=0;20=e;J{c=e-20;21+=7.4[i][c]*M[c][j]}H(--20);4[i][j]=21}H(--G)}H(--d);9 M=S.u(4);8 b?M.2E(1):M},x:l(a){8 7.22(a)},32:l(a,b,c,d){9 e=[],12=c,i,G,j;9 f=7.4.q,1p=7.4[0].q;J{i=c-12;e[i]=[];G=d;J{j=d-G;e[i][j]=7.4[(a+i-1)%f][(b+j-1)%1p]}H(--G)}H(--12);8 S.u(e)},31:l(){9 a=7.4.q,1p=7.4[0].q;9 b=[],12=1p,i,G,j;J{i=1p-12;b[i]=[];G=a;J{j=a-G;b[i][j]=7.4[j][i]}H(--G)}H(--12);8 S.u(b)},1y:l(){8(7.4.q==7.4[0].q)},2A:l(){9 m=0,12=7.4.q,15=12,i,G,10=7.4[0].q,j;J{i=15-12;G=10;J{j=10-G;o(F.13(7.4[i][j])>F.13(m)){m=7.4[i][j]}}H(--G)}H(--12);8 m},2Z:l(x){9 a=w,12=7.4.q,15=12,i,G,10=7.4[0].q,j;J{i=15-12;G=10;J{j=10-G;o(7.4[i][j]==x){8{i:i+1,j:j+1}}}H(--G)}H(--12);8 w},30:l(){o(!7.1y){8 w}9 a=[],n=7.4.q,k=n,i;J{i=k-n;a.19(7.4[i][i])}H(--n);8 v.u(a)},1K:l(){9 M=7.1q(),1c;9 n=7.4.q,k=n,i,1s,1n=7.4[0].q,p;J{i=k-n;o(M.4[i][i]==0){2e(j=i+1;j<k;j++){o(M.4[j][i]!=0){1c=[];1s=1n;J{p=1n-1s;1c.19(M.4[i][p]+M.4[j][p])}H(--1s);M.4[i]=1c;1I}}}o(M.4[i][i]!=0){2e(j=i+1;j<k;j++){9 a=M.4[j][i]/M.4[i][i];1c=[];1s=1n;J{p=1n-1s;1c.19(p<=i?0:M.4[j][p]-M.4[i][p]*a)}H(--1s);M.4[j]=1c}}}H(--n);8 M},3h:l(){8 7.1K()},2z:l(){o(!7.1y()){8 w}9 M=7.1K();9 a=M.4[0][0],n=M.4.q-1,k=n,i;J{i=k-n+1;a=a*M.4[i][i]}H(--n);8 a},3f:l(){8 7.2z()},2y:l(){8(7.1y()&&7.2z()===0)},2Y:l(){o(!7.1y()){8 w}9 a=7.4[0][0],n=7.4.q-1,k=n,i;J{i=k-n+1;a+=7.4[i][i]}H(--n);8 a},3e:l(){8 7.2Y()},1Y:l(){9 M=7.1K(),1Y=0;9 a=7.4.q,15=a,i,G,10=7.4[0].q,j;J{i=15-a;G=10;J{j=10-G;o(F.13(M.4[i][j])>17.16){1Y++;1I}}H(--G)}H(--a);8 1Y},3d:l(){8 7.1Y()},2W:l(a){9 M=a.4||a;o(1g(M[0][0])==\'1f\'){M=S.u(M).4}9 T=7.1q(),1p=T.4[0].q;9 b=T.4.q,15=b,i,G,10=M[0].q,j;o(b!=M.q){8 w}J{i=15-b;G=10;J{j=10-G;T.4[i][1p+j]=M[i][j]}H(--G)}H(--b);8 T},2w:l(){o(!7.1y()||7.2y()){8 w}9 a=7.4.q,15=a,i,j;9 M=7.2W(S.I(a)).1K();9 b,1n=M.4[0].q,p,1c,2v;9 c=[],2c;J{i=a-1;1c=[];b=1n;c[i]=[];2v=M.4[i][i];J{p=1n-b;2c=M.4[i][p]/2v;1c.19(2c);o(p>=15){c[i].19(2c)}}H(--b);M.4[i]=1c;2e(j=0;j<i;j++){1c=[];b=1n;J{p=1n-b;1c.19(M.4[j][p]-M.4[i][p]*M.4[j][i])}H(--b);M.4[j]=1c}}H(--a);8 S.u(c)},3c:l(){8 7.2w()},2d:l(){8 7.1b(l(x){8 F.2d(x)})},2V:l(x){8 7.1b(l(p){8(F.13(p-x)<=17.16)?x:p})},2n:l(){9 a=[];9 n=7.4.q,k=n,i;J{i=k-n;a.19(v.u(7.4[i]).2n())}H(--n);8 a.2K(\'\\n\')},26:l(a){9 i,4=a.4||a;o(1g(4[0][0])!=\'1f\'){9 b=4.q,15=b,G,10,j;7.4=[];J{i=15-b;G=4[i].q;10=G;7.4[i]=[];J{j=10-G;7.4[i][j]=4[i][j]}H(--G)}H(--b);8 7}9 n=4.q,k=n;7.4=[];J{i=k-n;7.4.19([4[i]])}H(--n);8 7}};S.u=l(a){9 M=25 S();8 M.26(a)};S.I=l(n){9 a=[],k=n,i,G,j;J{i=k-n;a[i]=[];G=k;J{j=k-G;a[i][j]=(i==j)?1:0}H(--G)}H(--n);8 S.u(a)};S.2X=l(a){9 n=a.q,k=n,i;9 M=S.I(n);J{i=k-n;M.4[i][i]=a[i]}H(--n);8 M};S.1R=l(b,a){o(!a){8 S.u([[F.1H(b),-F.1G(b)],[F.1G(b),F.1H(b)]])}9 d=a.1q();o(d.4.q!=3){8 w}9 e=d.1u();9 x=d.4[0]/e,y=d.4[1]/e,z=d.4[2]/e;9 s=F.1G(b),c=F.1H(b),t=1-c;8 S.u([[t*x*x+c,t*x*y-s*z,t*x*z+s*y],[t*x*y+s*z,t*y*y+c,t*y*z-s*x],[t*x*z-s*y,t*y*z+s*x,t*z*z+c]])};S.3b=l(t){9 c=F.1H(t),s=F.1G(t);8 S.u([[1,0,0],[0,c,-s],[0,s,c]])};S.39=l(t){9 c=F.1H(t),s=F.1G(t);8 S.u([[c,0,s],[0,1,0],[-s,0,c]])};S.38=l(t){9 c=F.1H(t),s=F.1G(t);8 S.u([[c,-s,0],[s,c,0],[0,0,1]])};S.2J=l(n,m){8 S.1j(n,m).1b(l(){8 F.2F()})};S.1j=l(n,m){9 a=[],12=n,i,G,j;J{i=n-12;a[i]=[];G=m;J{j=m-G;a[i][j]=0}H(--G)}H(--12);8 S.u(a)};l 14(){}14.23={24:l(a){8(7.1m(a)&&7.1h(a.K))},1q:l(){8 14.u(7.K,7.U)},2U:l(a){9 V=a.4||a;8 14.u([7.K.4[0]+V[0],7.K.4[1]+V[1],7.K.4[2]+(V[2]||0)],7.U)},1m:l(a){o(a.W){8 a.1m(7)}9 b=7.U.1C(a.U);8(F.13(b)<=17.16||F.13(b-F.1A)<=17.16)},1o:l(a){o(a.W){8 a.1o(7)}o(a.U){o(7.1m(a)){8 7.1o(a.K)}9 N=7.U.2f(a.U).2q().4;9 A=7.K.4,B=a.K.4;8 F.13((A[0]-B[0])*N[0]+(A[1]-B[1])*N[1]+(A[2]-B[2])*N[2])}1d{9 P=a.4||a;9 A=7.K.4,D=7.U.4;9 b=P[0]-A[0],2a=P[1]-A[1],29=(P[2]||0)-A[2];9 c=F.1x(b*b+2a*2a+29*29);o(c===0)8 0;9 d=(b*D[0]+2a*D[1]+29*D[2])/c;9 e=1-d*d;8 F.13(c*F.1x(e<0?0:e))}},1h:l(a){9 b=7.1o(a);8(b!==w&&b<=17.16)},2T:l(a){8 a.1h(7)},1v:l(a){o(a.W){8 a.1v(7)}8(!7.1m(a)&&7.1o(a)<=17.16)},1U:l(a){o(a.W){8 a.1U(7)}o(!7.1v(a)){8 w}9 P=7.K.4,X=7.U.4,Q=a.K.4,Y=a.U.4;9 b=X[0],1z=X[1],1B=X[2],1T=Y[0],1S=Y[1],1M=Y[2];9 c=P[0]-Q[0],2s=P[1]-Q[1],2r=P[2]-Q[2];9 d=-b*c-1z*2s-1B*2r;9 e=1T*c+1S*2s+1M*2r;9 f=b*b+1z*1z+1B*1B;9 g=1T*1T+1S*1S+1M*1M;9 h=b*1T+1z*1S+1B*1M;9 k=(d*g/f+h*e)/(g-h*h);8 v.u([P[0]+k*b,P[1]+k*1z,P[2]+k*1B])},1r:l(a){o(a.U){o(7.1v(a)){8 7.1U(a)}o(7.1m(a)){8 w}9 D=7.U.4,E=a.U.4;9 b=D[0],1l=D[1],1k=D[2],1P=E[0],1O=E[1],1Q=E[2];9 x=(1k*1P-b*1Q),y=(b*1O-1l*1P),z=(1l*1Q-1k*1O);9 N=v.u([x*1Q-y*1O,y*1P-z*1Q,z*1O-x*1P]);9 P=11.u(a.K,N);8 P.1U(7)}1d{9 P=a.4||a;o(7.1h(P)){8 v.u(P)}9 A=7.K.4,D=7.U.4;9 b=D[0],1l=D[1],1k=D[2],1w=A[0],18=A[1],1a=A[2];9 x=b*(P[1]-18)-1l*(P[0]-1w),y=1l*((P[2]||0)-1a)-1k*(P[1]-18),z=1k*(P[0]-1w)-b*((P[2]||0)-1a);9 V=v.u([1l*x-1k*z,1k*y-b*x,b*z-1l*y]);9 k=7.1o(P)/V.1u();8 v.u([P[0]+V.4[0]*k,P[1]+V.4[1]*k,(P[2]||0)+V.4[2]*k])}},1V:l(t,a){o(1g(a.U)==\'1f\'){a=14.u(a.1N(),v.k)}9 R=S.1R(t,a.U).4;9 C=a.1r(7.K).4;9 A=7.K.4,D=7.U.4;9 b=C[0],1E=C[1],1J=C[2],1w=A[0],18=A[1],1a=A[2];9 x=1w-b,y=18-1E,z=1a-1J;8 14.u([b+R[0][0]*x+R[0][1]*y+R[0][2]*z,1E+R[1][0]*x+R[1][1]*y+R[1][2]*z,1J+R[2][0]*x+R[2][1]*y+R[2][2]*z],[R[0][0]*D[0]+R[0][1]*D[1]+R[0][2]*D[2],R[1][0]*D[0]+R[1][1]*D[1]+R[1][2]*D[2],R[2][0]*D[0]+R[2][1]*D[1]+R[2][2]*D[2]])},1t:l(a){o(a.W){9 A=7.K.4,D=7.U.4;9 b=A[0],18=A[1],1a=A[2],2N=D[0],1l=D[1],1k=D[2];9 c=7.K.1t(a).4;9 d=b+2N,2h=18+1l,2o=1a+1k;9 Q=a.1r([d,2h,2o]).4;9 e=[Q[0]+(Q[0]-d)-c[0],Q[1]+(Q[1]-2h)-c[1],Q[2]+(Q[2]-2o)-c[2]];8 14.u(c,e)}1d o(a.U){8 7.1V(F.1A,a)}1d{9 P=a.4||a;8 14.u(7.K.1t([P[0],P[1],(P[2]||0)]),7.U)}},1Z:l(a,b){a=v.u(a);b=v.u(b);o(a.4.q==2){a.4.19(0)}o(b.4.q==2){b.4.19(0)}o(a.4.q>3||b.4.q>3){8 w}9 c=b.1u();o(c===0){8 w}7.K=a;7.U=v.u([b.4[0]/c,b.4[1]/c,b.4[2]/c]);8 7}};14.u=l(a,b){9 L=25 14();8 L.1Z(a,b)};14.X=14.u(v.1j(3),v.i);14.Y=14.u(v.1j(3),v.j);14.Z=14.u(v.1j(3),v.k);l 11(){}11.23={24:l(a){8(7.1h(a.K)&&7.1m(a))},1q:l(){8 11.u(7.K,7.W)},2U:l(a){9 V=a.4||a;8 11.u([7.K.4[0]+V[0],7.K.4[1]+V[1],7.K.4[2]+(V[2]||0)],7.W)},1m:l(a){9 b;o(a.W){b=7.W.1C(a.W);8(F.13(b)<=17.16||F.13(F.1A-b)<=17.16)}1d o(a.U){8 7.W.2k(a.U)}8 w},2k:l(a){9 b=7.W.1C(a.W);8(F.13(F.1A/2-b)<=17.16)},1o:l(a){o(7.1v(a)||7.1h(a)){8 0}o(a.K){9 A=7.K.4,B=a.K.4,N=7.W.4;8 F.13((A[0]-B[0])*N[0]+(A[1]-B[1])*N[1]+(A[2]-B[2])*N[2])}1d{9 P=a.4||a;9 A=7.K.4,N=7.W.4;8 F.13((A[0]-P[0])*N[0]+(A[1]-P[1])*N[1]+(A[2]-(P[2]||0))*N[2])}},1h:l(a){o(a.W){8 w}o(a.U){8(7.1h(a.K)&&7.1h(a.K.2j(a.U)))}1d{9 P=a.4||a;9 A=7.K.4,N=7.W.4;9 b=F.13(N[0]*(A[0]-P[0])+N[1]*(A[1]-P[1])+N[2]*(A[2]-(P[2]||0)));8(b<=17.16)}},1v:l(a){o(1g(a.U)==\'1f\'&&1g(a.W)==\'1f\'){8 w}8!7.1m(a)},1U:l(a){o(!7.1v(a)){8 w}o(a.U){9 A=a.K.4,D=a.U.4,P=7.K.4,N=7.W.4;9 b=(N[0]*(P[0]-A[0])+N[1]*(P[1]-A[1])+N[2]*(P[2]-A[2]))/(N[0]*D[0]+N[1]*D[1]+N[2]*D[2]);8 v.u([A[0]+D[0]*b,A[1]+D[1]*b,A[2]+D[2]*b])}1d o(a.W){9 c=7.W.2f(a.W).2q();9 N=7.W.4,A=7.K.4,O=a.W.4,B=a.K.4;9 d=S.1j(2,2),i=0;H(d.2y()){i++;d=S.u([[N[i%3],N[(i+1)%3]],[O[i%3],O[(i+1)%3]]])}9 e=d.2w().4;9 x=N[0]*A[0]+N[1]*A[1]+N[2]*A[2];9 y=O[0]*B[0]+O[1]*B[1]+O[2]*B[2];9 f=[e[0][0]*x+e[0][1]*y,e[1][0]*x+e[1][1]*y];9 g=[];2e(9 j=1;j<=3;j++){g.19((i==j)?0:f[(j+(5-i)%3)%3])}8 14.u(g,c)}},1r:l(a){9 P=a.4||a;9 A=7.K.4,N=7.W.4;9 b=(A[0]-P[0])*N[0]+(A[1]-P[1])*N[1]+(A[2]-(P[2]||0))*N[2];8 v.u([P[0]+N[0]*b,P[1]+N[1]*b,(P[2]||0)+N[2]*b])},1V:l(t,a){9 R=S.1R(t,a.U).4;9 C=a.1r(7.K).4;9 A=7.K.4,N=7.W.4;9 b=C[0],1E=C[1],1J=C[2],1w=A[0],18=A[1],1a=A[2];9 x=1w-b,y=18-1E,z=1a-1J;8 11.u([b+R[0][0]*x+R[0][1]*y+R[0][2]*z,1E+R[1][0]*x+R[1][1]*y+R[1][2]*z,1J+R[2][0]*x+R[2][1]*y+R[2][2]*z],[R[0][0]*N[0]+R[0][1]*N[1]+R[0][2]*N[2],R[1][0]*N[0]+R[1][1]*N[1]+R[1][2]*N[2],R[2][0]*N[0]+R[2][1]*N[1]+R[2][2]*N[2]])},1t:l(a){o(a.W){9 A=7.K.4,N=7.W.4;9 b=A[0],18=A[1],1a=A[2],2M=N[0],2L=N[1],2Q=N[2];9 c=7.K.1t(a).4;9 d=b+2M,2p=18+2L,2m=1a+2Q;9 Q=a.1r([d,2p,2m]).4;9 e=[Q[0]+(Q[0]-d)-c[0],Q[1]+(Q[1]-2p)-c[1],Q[2]+(Q[2]-2m)-c[2]];8 11.u(c,e)}1d o(a.U){8 7.1V(F.1A,a)}1d{9 P=a.4||a;8 11.u(7.K.1t([P[0],P[1],(P[2]||0)]),7.W)}},1Z:l(a,b,c){a=v.u(a);a=a.1N();o(a===w){8 w}b=v.u(b);b=b.1N();o(b===w){8 w}o(1g(c)==\'1f\'){c=w}1d{c=v.u(c);c=c.1N();o(c===w){8 w}}9 d=a.4[0],18=a.4[1],1a=a.4[2];9 e=b.4[0],1W=b.4[1],1X=b.4[2];9 f,1i;o(c!==w){9 g=c.4[0],2l=c.4[1],2t=c.4[2];f=v.u([(1W-18)*(2t-1a)-(1X-1a)*(2l-18),(1X-1a)*(g-d)-(e-d)*(2t-1a),(e-d)*(2l-18)-(1W-18)*(g-d)]);1i=f.1u();o(1i===0){8 w}f=v.u([f.4[0]/1i,f.4[1]/1i,f.4[2]/1i])}1d{1i=F.1x(e*e+1W*1W+1X*1X);o(1i===0){8 w}f=v.u([b.4[0]/1i,b.4[1]/1i,b.4[2]/1i])}7.K=a;7.W=f;8 7}};11.u=l(a,b,c){9 P=25 11();8 P.1Z(a,b,c)};11.2I=11.u(v.1j(3),v.k);11.2H=11.u(v.1j(3),v.i);11.2G=11.u(v.1j(3),v.j);11.36=11.2I;11.35=11.2H;11.3j=11.2G;9 $V=v.u;9 $M=S.u;9 $L=14.u;9 $P=11.u;', 62, 206, '||||elements|||this|return|var||||||||||||function|||if||length||||create|Vector|null|||||||||Math|nj|while||do|anchor||||||||Matrix||direction||normal||||kj|Plane|ni|abs|Line|ki|precision|Sylvester|A2|push|A3|map|els|else||undefined|typeof|contains|mod|Zero|D3|D2|isParallelTo|kp|distanceFrom|cols|dup|pointClosestTo|np|reflectionIn|modulus|intersects|A1|sqrt|isSquare|X2|PI|X3|angleFrom|mod1|C2|mod2|sin|cos|break|C3|toRightTriangular|false|Y3|to3D|E2|E1|E3|Rotation|Y2|Y1|intersectionWith|rotate|v12|v13|rank|setVectors|nc|sum|multiply|prototype|eql|new|setElements|case|each|PA3|PA2|part|new_element|round|for|cross|product|AD2|isSameSizeAs|add|isPerpendicularTo|v22|AN3|inspect|AD3|AN2|toUnitVector|PsubQ3|PsubQ2|v23|dot|divisor|inverse|true|isSingular|determinant|max|canMultiplyFromLeft|subtract|rows|col|random|ZX|YZ|XY|Random|join|N2|N1|D1|slice|default|N3|dimensions|switch|liesIn|translate|snapTo|augment|Diagonal|trace|indexOf|diagonal|transpose|minor|row|isAntiparallelTo|ZY|YX|acos|RotationZ|RotationY|liesOn|RotationX|inv|rk|tr|det|toDiagonalMatrix|toUpperTriangular|version|XZ'.split('|'), 0, {}));

var _T = {
    rotate: function (deg) {
        var rad = parseFloat(deg) * (Math.PI / 180),
            costheta = Math.cos(rad),
            sintheta = Math.sin(rad);

        var a = costheta,
            b = sintheta,
            c = -sintheta,
            d = costheta;

        return $M([
            [a, c, 0],
            [b, d, 0],
            [0, 0, 1]
        ]);
    },

    skew: function (dx, dy) {
        var radX = parseFloat(dx) * (Math.PI / 180),
            radY = parseFloat(dy) * (Math.PI / 180),
            c = Math.tan(radX),
            b = Math.tan(radY);


        return $M([
            [1, c, 0],
            [b, 1, 0],
            [0, 0, 1]
        ]);
    },

    translate: function (x, y) {
        var e = x || 0,
            f = y || 0;

        return $M([
            [1, 0, e],
            [0, 1, f],
            [0, 0, 1]
        ]);
    },

    scale: function (x, y) {
        var a = x || 0,
            d = y || 0;

        return $M([
            [a, 0, 0],
            [0, d, 0],
            [0, 0, 1]
        ]);
    },

    toString: function (m) {
        var s = 'matrix(',
            r, c;

        for (c = 1; c <= 3; c++) {
            for (r = 1; r <= 2; r++)
                s += m.e(r, c) + ', ';
        }

        s = s.substr(0, s.length - 2) + ')';

        return s;
    },

    fromString: function (s) {
        var t = /^matrix\((\S*), (\S*), (\S*), (\S*), (\S*), (\S*)\)$/g.exec(s),
            a = parseFloat(!t ? 1 : t[1]),
            b = parseFloat(!t ? 0 : t[2]),
            c = parseFloat(!t ? 0 : t[3]),
            d = parseFloat(!t ? 1 : t[4]),
            e = parseFloat(!t ? 0 : t[5]),
            f = parseFloat(!t ? 0 : t[6]);

        return $M([
            [a, c, e],
            [b, d, f],
            [0, 0, 1]
        ]);
    }
};


(function ($) {

    var console = window.console && window.SF_DEBUG ? window.console : {
        log: function () {
        }
    }
    var lh = location.href.replace(/\#.?/, '');

    var set = function (obj, prop, val) {
        Object.defineProperty(obj, prop, {
            value: val
        })
        return val;
    };

    $.fn.hasClasses = function (e) {
        var classes = e.replace(/\s/g, "").split(","),
            t = this;
        for (var i in classes) {
            if ($(t).hasClass(classes[i])) return true;
        }
        return false
    }

    $.fn.addClasses = function (e) {
        var classes = e.replace(/\s/g, "").split(","),
            t = this;
        for (var i in classes) $(t).addClass(classes[i]);
        return this;
    };


    jQuery(document).one('sfm_doc_body_arrived', function () {

        var $win = $(window);
        var $html = $('html');
        var $body = $('body');
        var originalWrite = document.write;
		var opts = window.SF_Opts;

        document.write = function () {
            console.log('Superfly plugin debug: using document.write is bad practice and not supported')
        };
        $body.prepend(window.SFM_template);
        document.write = originalWrite;

		var execFunc = opts.alt_menu ? $ : setTimeout;
		execFunc(function () {
            var LM = window.LM || (function () {
                    var isMobile = window.SFM_is_mobile;
					var clickTapEvent = isMobile ? ( $.mobile  ? 'vclick' : 'tap') : 'click'; // todo check android

                    var eventCancelTimeout;

                    var $rollback = $('.sfm-rollback');
                    var $sidebar = $('#sfm-sidebar');
                    var $overlay = $('#sfm-overlay-wrapper');
                    var $socialbar = $('.sfm-social', $sidebar);
                    var $defmenu = opts.alt_menu ? $(opts.alt_menu) : $('#sfm-nav');
                    var $custom = $('.sfm-view-level-custom'), customOpened = false;
                    var $cont;
                    var $head = $('.sfm-logo');
                    var sums = [];
                    sums.push(parseInt(opts.width_panel_1));
                    sums.push(sums[0] + parseInt(opts.width_panel_2));
                    sums.push(sums[1] + parseInt(opts.width_panel_3));
                    sums.push(sums[2] + parseInt(opts.width_panel_4));

                    if (opts.alt_menu && $defmenu.length) {
                        $('#sfm-nav').remove()
                    } else {
                        $defmenu = $('#sfm-nav')
                    }

                    //
                    if (SF_DEBUG) {
                        //$defmenu.find('a').attr('target', '_blank')
                    }

                    var direction = opts.sidebar_pos;
                    var opposite = direction === 'left' ? 'right' : 'left';
                    var pre = 'sfm';

                    var isIE = /msie|trident.*rv\:11\./.test(navigator.userAgent.toLowerCase());
                    var isFF = /firefox/.test(navigator.userAgent.toLowerCase());
                    var transProp = getVendorPropertyName('transform');
                    var translation = _T.translate((direction === 'left' ? opts.width_panel_1 : -opts.width_panel_1), 0);
                    var defTranslationStr = _T.toString(_T.translate(0, 0));
                    var htmlMargins = {
                        top: parseInt($html.css('marginTop')),
                        bottom: parseInt($html.css('marginBottom'))
                    }

                    var currentEvent = 'mouseenter';

                    var bbg = $body.css('backgroundImage');
                    var bodyCss;
                    var $bodybg;
                    var $children;

                    if (opts.sidebar_behaviour === 'push') {

                        if (bbg !== 'none') {

                            $body.prepend('<div id="sfm-body-bg"></div>');
                            $bodybg = $('#sfm-body-bg');

                            bodyCss = {
                                'backgroundColor': $body.css('backgroundColor'),
                                'backgroundImage': $body.css('backgroundImage'),
                                'backgroundAttachment': $body.css('backgroundAttachment'),
                                'backgroundSize': $body.css('backgroundSize'),
                                'backgroundPosition': $body.css('backgroundPosition'),
                                'backgroundRepeat': $body.css('backgroundRepeat'),
                                'backgroundOrigin': $body.css('backgroundOrigin'),
                                'backgroundClip': $body.css('backgroundClip')
                            };

                            if (bodyCss.backgroundColor.indexOf('(0, 0, 0, 0') + 1 || bodyCss.backgroundColor.indexOf('transparent') + 1) {
                                bodyCss.backgroundColor = '#fff';
                            }

                            $children = $body.children().not('#sfm-body-bg, #sfm-fixed-container, script, style');

                            if (parseInt($children.first().css('marginTop')) || parseInt($children.last().css('marginBottom'))) {
                                $body.addClass('sfm-body-float');
                            }

                            if (bodyCss.backgroundAttachment === 'fixed') {
                                bodyCss.position = 'fixed';
                                bodyCss.backgroundAttachment = 'scroll';
                            }

                            $bodybg.css(bodyCss);
                            attachStyles('body > * {position: relative} body {overflow-x:hidden!important}');
                        }
                    }

                    var menuOpts = {
                        search: opts.search,
                        addHomeLink: opts.addHomeLink === 'yes',
                        addHomeText: opts.addHomeText || 'Home',
                        subMenuSupport: opts.subMenuSupport === 'yes' /*&& opts.sidebar_style !== 'full'*/,
                        subMenuSelector: opts.subMenuSelector,
                        activeClassSelector: opts.activeClassSelector || '',
                        allowedTags: 'DIV, NAV, UL, OL, LI, A, P, H1, H2, H3, H4, SPAN, STRONG',
                        transitionDuration: 300,
                        extra: opts.menuData
                    }
                    //console.log(menuOpts)

                    var Menu = {
                        unique: 1,
                        build: function () {

                            var $newMenu;

                            $newMenu = $defmenu.clone().removeAttr("id class");
                            $newMenu = this.processDefMenu($newMenu);
                            $defmenu.remove();

                            if (menuOpts.addHomeLink) {
                                $newMenu.prepend('<li><a href="http://' + window.location.hostname + '">' + menuOpts.addHomeText + "</a></li>");
                            }

                            if ($newMenu.prop("tagName") === 'UL') {
                                $newMenu.addClass(pre + "-menu-level-0");
                            } else {
                                $newMenu.find("ul").first().addClass(pre + "-menu-level-0").siblings("ul").addClass(pre + "-menu-level-0");
                            }

                            menuOpts.subMenuSelector && menuOpts.subMenuSupport ? this.buildSubMenus($newMenu) : this.removeSubMenus($newMenu);

                            $newMenu.find('a').each(function () {
                                var $t = $(this);
                                if (!$t.children('span').length) $(this).wrapInner($('<span/>'));
                                if (!menuOpts.subMenuSupport) return;

                                if ($t.parent().is('.sfm-has-child-menu')) {
                                    $t.append('<ins class="sfm-sm-indicator"><i></i></ins>')
                                }
                            });

                            if (menuOpts.extra) this.attachExtraTo($newMenu.find("[class*=menu-item]"))

                            $newMenu.prependTo('.sfm-nav .sfm-va-middle').show();

                            if (menuOpts.search === 'show') {
                                $('.sfm-va-middle').prepend('<form role="search" method="get" class="sfm-search-form" action="' + opts.siteBase + '"><input type="text" class="search-field" placeholder="" value="" name="s"><span></span><input type="submit" class="search-submit" value="Search"></form>');
                            }
                            $cont = $("." + pre + "-nav");

                            $newMenu.removeClass(pre + "-has-child-menu").addClass('sfm-menu');
                        },

                        processDefMenu: function ($menu) {

                            var activeClassSelector = menuOpts.activeClassSelector ? menuOpts.activeClassSelector : "";
                            var classes = menuOpts.subMenuSelector ? menuOpts.subMenuSelector : "";
                            var tags = menuOpts.allowedTags.replace(/\s/g, "").split(",");
                            var $items = $menu.find("[class*=menu-item]");

                            $menu.find('.skip-link, .menu-toggle, a[title*="Skip to content"]').remove();

                            $items.each(function () {
                                var id = this.id ? this.id.replace('menu-item-', '') : (this.className.match(/menu-item-(\d+):?\b/) ? this.className.match(/menu-item-(\d+):?\b/)[1] : '');
                                $(this).data('sfm-id', id);
                            })

                            $menu.find("*").each(function () {
                                var $t = $(this);
                                var tag = $t.prop('tagName');
                                var cls = $t.prop('className');

                                if (tags.indexOf(tag) === -1 || $.trim($t.text()) === "" || $t.is('.uber-close')) {
                                    return $t.remove();
                                }

                                if ($t.hasClasses(classes)) {
                                    $t.removeAttr("class id").addClass(classes.split(",").join(' '));

                                } else {
                                    if ($t.hasClasses(activeClassSelector)) {
                                        $t.removeAttr("class id").addClass(pre + "-active-class");
                                    } else {
                                        $t.removeAttr("class id");
                                    }
                                }

                                $t.removeAttr("style");

                                if (tag === 'LI') {
                                    $t.addClass('sfm-menu-item-' + $t.data('sfm-id'));
                                }

                            });
                            return $menu;
                        },

                        buildSubMenus: function ($menu) {
                            var children = menuOpts.subMenuSelector.replace(/\s/g, "").split(",");

                            for (var i = 0, len = children.length; i < len; i++) {
                                $menu.find("." + children[i]).each(function () {
                                    var $t = $(this);
                                    $t.removeAttr("id class")
                                        .addClass(pre + "-child-menu ")
                                        .parent()
                                        .addClass(pre + "-has-child-menu")
                                });
                            }

                            this.detectLevel($menu)
                        },

                        attachExtraTo: function ($items) {
                            var data;
                            var https = location.protocol === 'https:';

                            $items.each(function () {
                                var $t = $(this);
                                var id = $t.data('sfm-id');
                                var data;
                                if (menuOpts.extra[id]) {
                                    data = deparam(menuOpts.extra[id]);
                                    if (data.hidemob && isMobile) {
                                        $t.remove();
                                        return;
                                    }
                                    if (data.img) {
                                        if (direction == 'right' && opts.sidebar_style == 'skew') {
                                            $t.find('> a').append('<img src="' + ( https ? data.img.replace('http:', 'https:') : data.img ) + '"/>')
                                        } else {
                                            $t.find('> a').prepend('<img src="' + ( https ? data.img.replace('http:', 'https:') : data.img ) + '"/>')
                                        }
                                    } else if (data.icon) {
                                        var style = data.icon_color ? 'color: ' + data.icon_color + ';' : '';
                                        var icon = data.icon
                                        var set = LAIconManagerUtil.getSet(icon) ? LAIconManagerUtil.getSet(icon) : 'Font Awesome';
                                        if (set === '####') {
                                            icon = LAIconManagerUtil.getIcon(icon)
                                            if (direction == 'right' && opts.sidebar_style == 'skew') {
                                                $t.find('> a').append('<i style="background-image:url(' + icon + ')" class="la_icon la_icon_manager_custom">');
                                            } else {
                                                $t.find('> a').prepend('<i style="background-image:url(' + icon + ')" class="la_icon la_icon_manager_custom">');
                                            }
                                        } else {
                                            icon = icon.indexOf('fa-') !== -1 ? 'la' + md5('Font Awesome') + '-' + icon.substr(3) : LAIconManagerUtil.getIconClass(icon);
                                            if (direction == 'right' && opts.sidebar_style == 'skew') {
                                                $t.find('> a').append('<i style="' + style + '" class="la_icon ' + icon + '">');
                                            } else {
                                                $t.find('> a').prepend('<i style="' + style + '" class="la_icon ' + icon + '">');
                                            }
                                        }
                                    }
                                    ;
                                    if (data.sline) {
                                        $t.find('> a span').append('<br><i class="sfm-sl">' + data.sline.replace(/\+/g, ' ') + '</i>');
                                    }
                                    ;
                                    if (data.chapter) {
                                        $t.before('<li class="sfm-chapter"><div>' + data.chapter.replace(/\+/g, ' ') + '</div></li>')
                                    }
                                    ;
                                    if (data.content) {
                                        if (!$t.find(".sfm-sm-indicator").length && menuOpts.subMenuSupport) {
                                            $t.addClass(pre + '-has-child-menu');
                                            $t.find(' > a').append('<ins class="sfm-sm-indicator"><i></i></ins>');
                                        }
                                    }
                                    $t.data('sfm-extra', data)
                                    if (data.width)    $t.attr('data-extra-width', data.width);
                                    if (data.bg)    $t.attr('data-bg', data.bg);
                                    $t.attr('data-sfm-id', id);
                                } else {
                                    return
                                }

                            })

                        },

                        detectLevel: function ($menu) {
                            $menu.find("." + pre + "-child-menu").each(function () {
                                var $t = $(this);
                                var t = $t.parents("." + pre + "-child-menu").length + 1;
                                $t.addClass(pre + "-menu-level-" + t);

                                if (!$sidebar.find('.' + pre + '-view-level-' + t).length) $sidebar.append($('<div class="sfm-view ' + pre + '-view-level-' + t + '"></div>'))
                            })
                        },

                        removeSubMenus: function ($menu) {
                            if (!menuOpts.subMenuSupport) {

                                return $menu.children().each(function () {
                                    $(this).find("ul").remove();
                                });
                            } else {
                                var o = menuOpts.subMenuSelector.replace(/\s/g, "").split(",");
                                for (var l in o) $menu.find("." + o[l]).each(function () {
                                    $(this).remove()
                                })
                            }
                        },

                        toggleActiveClasses: function ($menu) {
                            $menu.find("." + pre + "-has-child-menu").each(function () {
                                var $t = $(this);
                                if ($t.find("*").children("." + pre + "-active-class").length > 0) {
                                    $t.toggleClass(pre + "-child-menu-open");
                                    setTimeout(function () {
                                        $t.addClass(pre + "-child-menu-open");
                                        $t.find("." + pre + "-child-menu").first().show()
                                    }, menuOpts.transitionDuration);
                                }
                            })
                        }
                    };

                    var currLevel = 0;
                    var cursor;
                    var animTimer;
                    var state = 'hidden';
                    var events = 'off';
                    var ww;
                    var wh;
                    var maxWidth = 0;
                    var dragging = false;
                    var cancel = false;
                    var isIE9 = /MSIE 9/.test(navigator.userAgent);

                    function init() {

                        Menu.build();

                        if (!$sidebar.parent().is('body'))  $body.prepend($('#sfm-body-bg, .sfm-rollback, #sfm-sidebar, #sfm-mob-navbar, #sfm-overlay-wrapper'))

                        var viewsL = Math.min(1 + $sidebar.find('.sfm-view').length, 4);
                        for (var i = 1; i <= viewsL; i++) {
                            maxWidth += parseInt(opts['width_panel_' + i]);
                        }

                        if (opts.sidebar_behaviour === 'always') {
                            $sidebar.on('mouseenter ' + clickTapEvent, '.sfm-menu li:not(.sfm-chapter)', itemEventHandler);
                        }
                        else if (opts.sidebar_style === 'full') {
                            $sidebar.on(clickTapEvent, '.sfm-menu li:not(.sfm-chapter)', itemEventHandler);
                        }
                        else {
                            $sidebar.bind(window.transitionEnd, function (e) {

                                if (!$(e.target).is($sidebar)) return;

                                if ($sidebar.is('.sfm-sidebar-exposed') && events === 'off') {

                                    $sidebar.on('mouseenter ' + clickTapEvent, '.sfm-menu li:not(.sfm-chapter)', itemEventHandler);

                                    events = 'on';
                                    //todo check cursor

                                } else if (!$sidebar.is('.sfm-sidebar-exposed')) {

                                    $sidebar.off('mouseenter ' + clickTapEvent, '.sfm-menu-level-0 > li, .sfm-view ul > li');
                                    events = 'off';
                                    $overlay.css('visibility', 'hidden');

                                    if (opts.sidebar_behaviour === 'push') {

                                        //setTimeout(function(){
                                        var $fixed = $('.sfm-inner-fixed')
                                        if (!isIE) $fixed.each(unshiftFixed);
                                        $fixed.removeClass('sfm-inner-fixed');
                                        //}, 0)
                                    }
                                }
                            });
                        }

                        if (isIE9) {
                            $sidebar.on('mouseenter', '.sfm-menu-level-0 > li:not(.sfm-chapter), .sfm-view ul > li:not(.sfm-chapter)', itemEventHandler);
                        }

                        $('.sfm-view').mouseenter(function () {
                            clearTimeout(animTimer);
                        });

                        $custom.on((opts.sub_opening_type === 'hover' && !isMobile && opts.sub_type !== 'yes') ? 'mouseenter' : clickTapEvent, function () {
                            cursor = this;
                        });

                        if (opts.opening_type === 'hover' && !isMobile) {

                            if (opts.sidebar_behaviour !== 'always') {
                                $rollback.mouseenter(function (e) {
                                    if (!$(e.relatedTarget).is('.sfm-sidebar-close')) showSidebar()
                                });
                            }

                            $sidebar.mouseleave(function (e) {
                                setTimeout(function () {
                                    hideSidebar()
                                }, 250)
                            });

                        } else if (opts.opening_type === 'click' || isMobile || opts.sub_type === 'yes') {

                            $('#sfm-overlay').on(clickTapEvent, hideSidebar);

                            if (opts.sub_opening_type === 'hover') {
                                $sidebar.mouseleave(function (e) {
                                    setTimeout(function () {
                                        $body.removeClass('sfm-view-pushed-custom sfm-view-pushed-1 sfm-view-visible-1 sfm-view-pushed-2 sfm-view-visible-2 sfm-view-pushed-3 sfm-view-visible-3');
                                        hideCustom(true);
                                        $sidebar.find('.sfm-active-item').removeClass('sfm-active-item');
                                        if (opts.sidebar_behaviour === 'always') {
                                            setTimeout(function () {
                                                $overlay.css('visibility', 'hidden')
                                            }, 400);
                                        }
                                    }, 250)
                                });
                            }
                        }

                        if (opts.togglers) {
                            /*
                            var $togglers = $body.find(opts.togglers);
                            if ($togglers) {

                            }
                            */
                            $body.on(clickTapEvent, opts.togglers, function (e) {

                                // preventing immediate double tap events occuring in some themes
                                if (eventCancelTimeout) {
                                    return;
                                } else {
                                    eventCancelTimeout = setTimeout(function () {
                                        eventCancelTimeout = null
                                    }, 10)
                                }
                                
                                e.preventDefault();
                                e.stopImmediatePropagation();
                                state === 'hidden' ? showSidebar() : hideSidebar();
                                return false;
                            });
                        }

                        $sidebar.on('click', '.sfm-menu a', function (e) {
                            console.log('preventing');
                            if (!isIE9) e.preventDefault();
                        });

                        $win.on('resize orientationchange', setupSize);


                        $sidebar.on("touchmove", function () {
                            dragging = true;
                        });

                        $sidebar.on("touchend", function () {
                            dragging = false;
                        });

                        $rollback.find('.sfm-navicon-button').add('#sfm-mob-navbar .sfm-navicon-button, .sfm-sidebar-close').on(clickTapEvent, function (e) {
                            //e.stopImmediatePropagation();

                            if (state === 'hidden') {
                                showSidebar();
                                $(this).addClass("sfm-open");
                            } else {
                                hideSidebar();
                                $(this).removeClass("sfm-open");
                            }

                            return false;
                        });

                        $('#sfm-sidebar .sfm-search-form span').on(clickTapEvent, function () {
                            console.log('yo')
                            $(this).closest('form').submit()
                        })

                        if (isMobile) {
                            if (/iPad|iPhone/.test(navigator.userAgent)) {
                                $sidebar.on('focus', 'input', function (e) {
                                    $body.css('overflowX', 'visible');
                                    $sidebar.css({'position': 'absolute', top: '-62px'})
                                    $(document).scrollTop(0);

                                }).on('blur', 'input', function () {
                                    $sidebar.css('position', '');
                                    $body.css('overflowX', 'hidden');
                                });
                            }
                            attachSwipesHandler();
                        }

                        populateSocialBarWith(opts.social);

                        var behaviour = opts.sidebar_style == 'full' ? 'full' : opts.sidebar_behaviour;

                        var bodyClasses = 'superfly-on sfm-style-' + opts.sidebar_style + ' sfm-sidebar-' + behaviour + ' sfm-toggle-' + opts.opening_type + (opts.blur === 'blur' ? ' sfm-blur' : '');
                        console.log(bodyClasses);
                        $body.addClass(bodyClasses);

                        $( document ).ajaxComplete(function() {
                            setTimeout(function(){
                                if(!$body.is('.superfly-on')) {
                                    $body.addClass(bodyClasses);
                                }
                            }, 0);
                        });

                        setTimeout(function () {

                            if (opts.sidebar_behaviour === 'always') {
                                setTimeout(function () {
                                    setupSize();
                                    $sidebar.css('opacity', '1');
                                }, 300);
                            } else {
                                $sidebar.css('opacity', '1');
                            }
                        }, 0);

                        if (opts.test_mode === 'yes') {
                            $sidebar.addClass('sfm-test-mode');
                        }

                        $('.sfm-close').bind(clickTapEvent, function () {
                            $custom.removeClass('sfm-modal');
                            var css = {'opacity': '', 'visibility': ''};
                            $custom.css(css);
                            $custom.find('.sfm-active').removeClass('sfm-active');
                        })

						// fix for CF7
						$(function(){
							setTimeout(function(){
								var $cf7form = $('#sfm-sidebar .wpcf7 form');
								if (!$cf7form.length) return;
								var evObj = $._data( $('#sfm-sidebar .wpcf7 form')[0], "events" );
								if (!evObj) {
									$('div.wpcf7 > form').wpcf7InitForm();
								}
							},0)
						});

                        LM.init = function () {
                        };
                        return this;
                    }

                    function hideCustom(reset) {
                        if (customOpened) {
                            var css = {'opacity': '', 'visibility': '', transProp: '', backgroundColor: ''};
                            if (reset) {
                                css[transProp] = defTranslationStr;
                                css['width'] = '';
                            }
                            else css[transProp] = $custom.data('startPos') || defTranslationStr;
                            $custom.css(css)//.data('startPos', '');
                            $custom.find('.sfm-active').removeClass('sfm-active');
                        }
                    }

                    function setupFont() {
                        var wh = window.innerHeight || document.documentElement.offsetHeight || document.documentElement.clientHeight;
                        var th = wh - $('.sfm-logo').outerHeight() - $('.sfm-social').outerHeight() - 60;
                        var th = wh - ($('.sfm-logo img').length ? 80 : 0 ) - ($('.sfm-social').children().length ? 85 : 0 ) - 30;
                        var $links = $sidebar.find('.sfm-nav .sfm-menu li > a');
                        var num = $links.length;
                        var space = th / num;
                        var line = Math.min(space - opts.item_padding * 2, isMobile ? 45 : 65);
                        $links.css({'fontSize': line, 'lineHeight': space - opts.item_padding + 'px'})
                        //$links.css('lineHeight', space + 'px')
                        //console.log('line', th, space, space - opts.item_padding * 2, line)
                    }

                    function showSidebar() {
                        var $children;
                        if (state !== 'hidden') return;

                        clearTimeout(animTimer);
                        setupSize();

                        $sidebar.addClass('sfm-sidebar-exposed')
                        $rollback.find('.sfm-navicon-button').add('#sfm-mob-navbar .sfm-navicon-button').addClass('sfm-open');
                        $overlay.css('visibility', 'visible');

                        /*animTimer = */

                        $body.addClass('sfm-body-pushed');

                        if (opts.sidebar_behaviour === 'push' && opts.sidebar_style != 'full') {

                            $children = $body.children().not('[id*=sfm-], script, style');

                            $children.find('*').each(function (i, el) {
                                shiftFixed(i, el, $win.scrollTop(), $win.scrollLeft())
                            });
                        }

                        state = 'open';

                        return false;
                    }

                    function hideSidebar() {
                        clearTimeout(animTimer);
                        if (isIE && (opts.sidebar_behaviour === 'push')) {
                            $('.sfm-inner-fixed').each(unshiftFixed);
                        }

                        if (opts.sidebar_behaviour === 'always' || opts.sidebar_style === 'full') {
                            setTimeout(function () {
                                $overlay.css('visibility', 'hidden')
                            }, 400);
                        }
                        $sidebar.find('.sfm-active-item').removeClass('sfm-active-item');

                        hideCustom(true);

                        //animTimer = setTimeout(function(){

                        $body.removeClass('sfm-body-pushed sfm-view-pushed-custom sfm-view-pushed-1 sfm-view-pushed-2 sfm-view-pushed-3  sfm-view-pushed-4 sfm-view-visible-1 sfm-view-visible-2 sfm-view-visible-3 sfm-view-visible-4')
                        $sidebar.removeClass('sfm-sidebar-exposed')
                        $rollback.find('.sfm-navicon-button').add('#sfm-mob-navbar .sfm-navicon-button').removeClass('sfm-open');
                        state = 'hidden';
                        //}, 75);
                    }

                    function itemEventHandler(e) {

                        // preventing immediate double tap events occuring in some themes
                        if (eventCancelTimeout) {
                            return;
                        } else {
                            eventCancelTimeout = setTimeout(function () {
                                eventCancelTimeout = null
                            }, 10)
                        }

                        e.stopImmediatePropagation();

                        var $t = $(this);
                        var $tar;

                        function goToLink() {

                            var $a = $t.find('a');
                            var hr = $a.prop('href');
                            var blank = $a.attr('target') === '_blank';
                            var hash = $a.prop('hash');
                            var smooth = hash && hash.length > 0 && hr == lh + hash, scrollTop, $el;
                            if (smooth) {
                                hideSidebar();
                                /*if (opts.sidebar_style === 'static') {
                                 $sidebar.find('.sfm-active-smooth').removeClass('sfm-active-smooth');
                                 $t.addClass('sfm-active-smooth');
                                 }*/
                                if (hash !== '#') {
                                    $el = $(hash);
                                    if (!$el.length) {
                                        $el = $('[name="' + hash.replace('#', '') + '"]');
                                    }
                                }
                                scrollTop = $el && $el.length ? $el.offset().top : 0;
                                $('html, body').stop().animate({
                                    scrollTop: scrollTop
                                }, 600);
                            }
                            else if (blank) {
                                window.open(hr, '_blank');
                            } else {
                                //hideSidebar();
                                if (opts.fade === 'yes') {
                                    //TODO don't fade when #
                                    if (hr.indexOf('#') !== -1 && hash === '') {
                                        return
                                    }
                                    $body.fadeOut(200, function () {
                                        location.href = hr
                                    });
                                } else {
                                    location.href = hr
                                }
                            }
                        }

                        if (e.type === clickTapEvent) {
                            if (currentEvent === 'mouseenter') {
                                goToLink();
                            } else {
                                $tar = $(e.target);
                                if ($tar.closest('.sfm-sm-indicator').length || isMobile || opts.sub_type === 'yes' || opts.sidebar_style === 'full') {
                                    console.log('ev 1', e.target)
                                    eventFor($t, e);
                                } else {
                                    e.stopImmediatePropagation();
                                    goToLink();
                                }
                            }

                        }
                        else if (e.type !== currentEvent) {
                            return;
                        } else {
                            var _cursor = this;
                            var timer = setTimeout(function () {
                                if (_cursor === cursor) {
                                    eventFor($t, e);
                                }
                            }, 225);

                            cursor = this;
                        }


                        //e.stopImmediatePropagation();
                        //return false
                    }

                    function eventFor($t, e) {

                        if (dragging) {
                            return;
                        }
                        console.log('event for')

                        cancel = false;
                        clearTimeout(animTimer);

                        var level = parseInt(($t.closest('ul').attr('class') || '0').match(/\d/)[0]) + 1;
                        var $sub, $et;
                        var $sibs = $t.siblings('.sfm-active-item'), css;
                        var $a = $t.find('a');
                        var hr, blank, hash, smooth, scrollTop;
                        var _ww = ww;
                        var _wh = wh;
                        var id;
                        var startPos, customWidth, calcWidth, customBg, $currCont, classes;

                        hideCustom(level <= currLevel);

                        if ($t.is('.sfm-has-child-menu')) {

                            //console.log('child menu item');
                            $sub = $t.children('.sfm-child-menu').first();

                            if ($sub.length) {

                                if (maxWidth < _ww && !isMobile && opts.sub_type !== 'yes' && opts.sidebar_style !== 'full') {

                                    cancel = true;

                                    if ($body.is('.sfm-view-pushed-' + level) && !$sibs.length) {
                                        cancel = false;
                                    }

                                    $sibs.removeClass('sfm-active-item');

                                    $body.removeClass('sfm-view-pushed-custom sfm-view-pushed-' + (level + 1) + ' sfm-view-visible-' + (level + 1) + ' sfm-view-pushed-' + (level + 2) + ' sfm-view-visible-' + (level + 2));

                                    if (!$sidebar.is('.sfm-sidebar-exposed') && opts.sidebar_behaviour !== 'always') return;
                                    $('.sfm-view-level-' + level).attr('class',
                                        function (i, c) {
                                            return c.replace(/(^|\s)sfm-current-\S+/g, '');
                                        }).html('<ul class="sfm-menu-level-' + level + ' sfm-menu">' + $sub.html() + '</ul>').addClass('sfm-current-' + $t.data('sfmId'));

                                    animTimer = setTimeout(function () {
                                        $body.addClass('sfm-view-pushed-' + level);
                                        if (opts.sidebar_behaviour === 'always') $overlay.css('visibility', 'visible');
                                        currLevel = level;
                                    }, 25);

                                    $t.addClass('sfm-active-item');

                                } else {
                                    $et = $(e.target);
                                    hr = $a.attr('href');
                                    var hash = $a.prop('hash');
                                    if ($et.closest('.sfm-sm-indicator').length || hash === '#' || hr === '#' || hr === '/') {

                                        if (!$t.is('.sfm-submenu-visible')) {
                                            console.log('sub', $sub)
                                            $t.siblings().filter('.sfm-submenu-visible').removeClass('sfm-submenu-visible').find('> ul').slideUp()
                                            $t.addClass('sfm-submenu-visible');
                                            $sub.slideDown();
                                            cancel = true;
                                        } else {
                                            if ($a.length && e.type === clickTapEvent) {
                                                $t.removeClass('sfm-submenu-visible');
                                                $sub.slideUp();
                                                cancel = true;
                                            }
                                        }
                                    }
                                }
                            } else {
                                // Custom content
                                customWidth = $t.attr('data-extra-width');
                                customBg = $t.attr('data-bg');
                                calcWidth = parseInt(customWidth || opts['width_panel_' + (level + 1)]);

                                if (sums[level - 1] + calcWidth < _ww && opts.sidebar_style !== 'full') {
                                    cancel = true;
                                    $currCont = $custom.find('#sfm-cc-' + $t.attr('data-sfm-id'));

                                    if ($body.is('.sfm-view-pushed-' + level) && !$sibs.length) {
                                        cancel = false;
                                    }

                                    $sibs.removeClass('sfm-active-item');
                                    $body.removeClass('sfm-view-pushed-custom sfm-view-pushed-' + level + ' sfm-view-visible-' + level + ' sfm-view-pushed-' + (level + 1) + ' sfm-view-visible-' + (level + 1))
                                        .addClass('sfm-view-pushed-custom');

                                    if (!$sidebar.is('.sfm-sidebar-exposed') && opts.sidebar_behaviour !== 'always') return;

                                    if (opts.sidebar_behaviour === 'always') $overlay.css('visibility', 'visible');

                                    $t.addClass('sfm-active-item');

                                    if (isMobile || opts.sub_type === 'yes') {
                                        startPos = _T.toString(_T.translate(0));
                                        console.log(startPos)
                                    } else {
                                        startPos = _T.toString(_T.translate(opts.sidebar_pos === 'right' ? sums[level - 1] - sums[0] : sums[level - 1] - calcWidth));
                                    }

                                    css = {
                                        'opacity': 1,
                                        'visibility': 'visible',
                                        'backgroundColor': customBg,
                                        'width': calcWidth
                                    };
                                    css[transProp] = startPos;
                                    $custom.css(css).data('startPos', startPos);

                                    $custom.find('.sfm-active').removeClass('sfm-active');

                                    $currCont.width(calcWidth);

                                    if (_wh > $currCont.outerHeight()) {
                                        $currCont.addClass('sfm-vert-align sfm-active');
                                    } else {
                                        $currCont.removeClass('sfm-vert-align').addClass('sfm-active');
                                    }

                                    setTimeout(function () {
                                        if (isMobile || opts.sub_type === 'yes') {
                                            $custom.css(transProp, _T.toString(_T.translate(sums[0])));
                                        } else {
                                            $custom.css(transProp, _T.toString(_T.translate(opts.sidebar_pos === 'right' ? -calcWidth - (sums[level - 1] - sums[0]) : sums[level - 1])));
                                        }
                                    }, 0);

                                    customOpened = true;

                                } else {
                                    // open modal for custom content
                                    $et = $(e.target);

                                    if ($et.closest('.sfm-sm-indicator').length || isMobile || opts.sub_type === 'yes') {
                                        if (!$t.is('.sfm-submenu-visible')) {
                                            console.log('sub', $sub);
                                            customBg = $t.attr('data-bg');
                                            $t.siblings().filter('.sfm-submenu-visible').removeClass('sfm-submenu-visible').find('> ul').slideUp()

                                            $custom.addClass('sfm-modal');
                                            css = {
                                                'opacity': 1,
                                                'visibility': 'visible',
                                                'backgroundColor': customBg,
                                                'width': _ww
                                            };
                                            $custom.find('.sfm-active').removeClass('sfm-active');
                                            $custom.find('#sfm-cc-' + $t.attr('data-sfm-id')).addClass('sfm-active')
                                            $custom.css(css);
                                            cancel = true;
                                        }
                                    }
                                }
                            }

                        } else {

                            if (maxWidth < _ww + 200) {
                                //console.log('siblings', $t.siblings('.sfm-active-item').length)
                                $t.siblings('.sfm-active-item').removeClass('sfm-active-item');

                                animTimer = setTimeout(function () {
                                    $body.removeClass('sfm-view-pushed-custom sfm-view-pushed-' + level + ' sfm-view-visible-' + level + ' sfm-view-pushed-' + (level + 1) + ' sfm-view-visible-' + (level + 1) + ' sfm-view-pushed-' + (level + 2) + ' sfm-view-visible-' + (level + 2));
                                }, 50);
                            }

                        }

                        var ev = isIE9 ? 'mouseenter' : clickTapEvent;


                        if ($a.length && e.type === ev && !cancel && !isIE9) {
                            hr = $a.prop('href');
                            blank = $a.attr('target') === '_blank';
                            hash = $a.prop('hash');
                            smooth = hash && hash.length > 1 && hr == lh + hash, scrollTop;

                            if (smooth) {
                                hideSidebar();
                                $t.addClass('sfm-active-item');
                                scrollTop = $a.prop('hash') === '#' ? 0 : $($a.prop('hash')).offset().top;
                                $('html, body').stop().animate({
                                    scrollTop: scrollTop
                                }, 600);
                            }
                            else if (blank) {
                                window.open(hr, '_blank');
                            } else {
                                // todo
                                //$body.fadeOut(200,function(){location.href = hr});
                                location.href = hr
                            }
                        }
                    }

                    function setupSize(e) {
                        wh = window.innerHeight ? window.innerHeight : $win.height();
                        ww = window.innerWidth ? window.innerWidth : $win.width();
                        var init = !setupSize.cache;

                        if (init) {
                            $sidebar.addClass('sfm-compact')
                        }

                        var margin = parseInt(opts.item_padding) * 2;

                        setupSize.cache = setupSize.cache || {
                                hh: $head.is(':empty') ? 0 : $head.outerHeight() + 70 + margin,
                                sh: $socialbar.is(':empty') ? 0 : 145 + margin,
                                ch: $cont.outerHeight()
                            };

                        if (init) {
                            $sidebar.removeClass('sfm-compact')
                        }
                        var ch = setupSize.cache.ch;
                        var hh = setupSize.cache.hh;
                        var sh = setupSize.cache.sh;
                        var ah = ( wh - ch ) / 2;
                        var classesToAdd = '';
                        var classesToRemove = '';
                        if (ah < hh || ah < sh || isMobile || opts.sub_type === 'yes') {
                            if (opts.sidebar_style !== 'full') {
                                classesToAdd = 'sfm-compact';
                                if (ah < hh) {
                                    classesToAdd += ' sfm-compact-header';
                                    if (ah > sh) {
                                        classesToRemove = 'sfm-compact-footer';
                                    }
                                }
                                if (ah < sh) {
                                    classesToAdd += ' sfm-compact-footer'
                                    if (ah > hh) {
                                        classesToRemove = 'sfm-compact-header';
                                    }
                                }
                            }
                        } else {
                            classesToRemove = 'sfm-compact';
                        }

                        if (maxWidth > ww || isMobile || opts.sub_type === 'yes' || opts.sidebar_style === 'full') {
                            classesToAdd += ' sfm-vertical-nav';
                            currentEvent = clickTapEvent;
                        } else {
                            classesToRemove += ' sfm-vertical-nav';
                            currentEvent = 'mouseenter';
                        }

                        if (navigator.appVersion.indexOf("Mac") != -1) {
                            classesToAdd += ' sfm-mac';
                        }

                        if (classesToAdd) $sidebar.addClass(classesToAdd);
                        if (classesToRemove && !init) $sidebar.removeClass(classesToRemove);
                        opts.sidebar_style === 'full' && setupFont(wh);
                    }

                    function shiftFixed(i, el, scrollTop, scrollLeft, bh, wh) {

                        var $t = $(el);
                        var $offsetP;
                        var t;
                        var nu;
                        var offset;
                        var oLeft;
                        var oTop;
                        var coords;
                        var newCSS;
                        var _b;
                        var transf;

                        if ($t.css('position') === 'fixed') {

                            $t.addClass('sfm-inner-fixed');

                            if (isIE) {

                                t = $t.css(transProp);

                                if (t !== 'none') {
                                    $t.data('sfm-old-matrix', t);
                                    t = _T.fromString(t);
                                    nu = t.x(translation); // add translation
                                    $t.css(transProp, _T.toString(nu)).data('sfm-transformed', 1);
                                } else {
                                    $t.css(transProp, _T.toString(translation)/*, transition: trans + 'transform 0.5s cubic-bezier(0.645, 0.045, 0.355, 1)', transitionDelay: '90ms'*/).data('sfm-transformed', 1);
                                }

                            } else {

                                $offsetP = $t;

                                while ($offsetP = $offsetP.parent()) {
                                    transf = $offsetP.css('webkitTransform');
                                    if ((transf && transf !== 'none') || $offsetP.is('body')) {
                                        break
                                    }
                                }

                                //console.log('offset parent' , $offsetP[0])

                                offset = $offsetP.offset();
                                oLeft = offset.left;
                                oTop = offset.top;
//if (oTop === htmlMargins.top) oTop = 0;

                                if (isFF && $t.is(':visible')) {
                                    $t.hide().data('sfm-ff-hidden', 1);
                                }

                                coords = {
                                    left: $t.css('left'),
                                    right: $t.css('right'),
                                    top: $t.css('top'),
                                    bottom: $t.css('bottom')
                                }

                                if (isFF && $t.data('sfm-ff-hidden')) $t.show();

                                newCSS = {};
                                _b = parseInt(coords.bottom);
                                _b = isNaN(_b) ? 0 : _b;

                                if (coords.left !== 'auto') {
                                    coords.toChangeHor = 'left';
                                    newCSS[coords.toChangeHor] = '-=' + (oLeft - scrollLeft);
                                } else if (coords.right !== 'auto') {
                                    coords.toChangeHor = 'right';
                                    newCSS[coords.toChangeHor] = '-=' + (oLeft - scrollLeft);
                                } else {
                                    coords.toChangeHor = 'left'
                                }

                                if (coords.top !== 'auto') {
                                    coords.toChangeVert = 'top';
                                    newCSS[coords.toChangeVert] = oTop - scrollTop > 0 ? parseInt(coords.top) - (oTop - scrollTop) : parseInt(coords.top) + (scrollTop - oTop);
                                } else if (coords.bottom !== 'auto') {
                                    coords.toChangeVert = 'bottom';
                                    newCSS[coords.toChangeVert] = $body.height() + htmlMargins.top + htmlMargins.bottom + _b - $win.height() - scrollTop + 'px';
                                } else {
                                    coords.toChangeVert = 'top';
                                    newCSS[coords.toChangeVert] = scrollTop;
                                }

//							console.log('transf', transProp);
//							console.log('el', el);
//							console.log('parent', $offsetP[0]);
//							console.log('coords', coords);
//							console.log('newCSS', newCSS);
//							console.log('offsetTop', oTop);
//							console.log(scrollTop, scrollLeft);

                                $t.css(newCSS).data('sfm-old-pos', coords)
                            }
                        }
                    }

                    function unshiftFixed(i, el) {
                        var $el = $(el);
                        var coords;
                        var newCss;
                        if (isIE) {
                            if ($el.data('sfm-old-matrix')) {
                                $el.css(transProp, $el.data('sfm-old-matrix')).data('sfm-old-matrix', '');
                            } else {
                                $el.css(transProp, defTranslationStr).data('sfm-transformed', '');
                            }
                        } else {
                            coords = $el.data('sfm-old-pos');
                            //console.log('coords', coords);
                            newCss = {};
                            if (coords) {
                                newCss[coords.toChangeHor] = coords[coords.toChangeHor];
                                newCss[coords.toChangeVert] = coords[coords.toChangeVert];
                                if (coords.toChangeVert === 'bottom') newCss['top'] = '';
                                $el.css(newCss);
                                $el.data('sfm-old-pos', '');
                            } else {
                                $el.css({left: '', top: '', bottom: '', right: ''})
                            }
                        }
                    }


                    function populateSocialBarWith(social) {
                        var name;
                        for (name in social) {
                            if (social.hasOwnProperty(name)) {
                                if (name === 'skype') {
                                    $('<li class="sfm-icon-' + name + '"><a href="skype:' + social[name] + '?call"></a></li>').appendTo($socialbar);
                                } else if (name === 'email') {
                                    $('<li class="sfm-icon-' + name + '"><a href="mailto:' + social[name] + '"></a></li>').appendTo($socialbar);
                                } else {
                                    $('<li class="sfm-icon-' + name + '"><a href="' + social[name] + '" target="_blank"></a></li>').appendTo($socialbar);
                                }
                            }
                        }
                    }

                    function attachSwipesHandler() {
                        var startX, startY, startTime, moveX, moveY;
                        $sidebar.add($overlay).bind('touchstart', function (e) {
                                if (state === 'open') {
                                    startTime = (new Date).getTime();
                                    startX = e.originalEvent.touches[0].pageX;
                                    startY = e.originalEvent.touches[0].clientY;
                                }
                            })
                            .bind('touchmove', function (e) {
                                if (state === 'open') {
                                    moveX = e.originalEvent.touches[0].pageX;
                                    moveY = e.originalEvent.touches[0].clientY
                                }
                            })
                            .bind('touchend', function () {
                                if (state === 'open') {
                                    var swipeDirection = moveX > startX ? "right" : "left";
                                    var finalY = moveY - startY > 30 || -30 > moveY - startY;
                                    var finalX = moveX - startX > 60 || -60 > moveX - startX;
                                    var now = (new Date).getTime();
                                    if (!(now - startTime > 200 || finalY) && finalX) {
                                        switch (swipeDirection) {
                                            case "left":
                                                "left" === direction ? hideSidebar() : showSidebar();
                                                break;
                                            case "right":
                                                "left" === direction ? showSidebar() : hideSidebar()
                                        }
                                    }
                                }
                            });
                    }

                    function freezeBodyScroll(e) {
                        var scrollTo = null;

                        if (e.type == 'mousewheel') {
                            scrollTo = (e.originalEvent.wheelDelta * -1);
                        }
                        else if (e.type == 'DOMMouseScroll') {
                            scrollTo = 40 * e.originalEvent.detail;
                        }

                        if (scrollTo) {
                            e.preventDefault();
                            $(this).scrollTop(scrollTo + $(this).scrollTop());
                        }
                    }

                    function freezeBody(e) {
                        if (e.type == 'mousewheel' || e.type == 'DOMMouseScroll') {
                            e.preventDefault()
                        }
                    }

                    function checkOrientation() {
                        var o = window.orientation;
                        if (o) {
                            if (o != 90 && o != -90) {
                                return 'portrait';
                            } else {
                                return 'landscape';
                            }
                        } else {
                            if ($win.height() > $win.width()) {
                                return 'portrait';
                            } else {
                                return 'landscape';
                            }
                        }
                    }


                    return {
                        init: init,
                        showSidebar: showSidebar,
                        hideSidebar: hideSidebar,
                        getState: function () {
                            return state
                        }
                    }
                }());

            window.LM = LM.init();


        }, 0);

    });

	if (window.SFM_EVENT_DISPATCHED) $(document).trigger('sfm_doc_body_arrived');

    function attachStyles(t) {
        if (document.body) {
            var s = document.createElement('style');
            s.type = 'text/css';
            if (/WebKit|MSIE/i.test(navigator.userAgent)) {
                if (s.styleSheet) {
                    s.styleSheet.cssText = t;
                } else {
                    s.innerText = t;
                }
            } else {
                s.innerHTML = t;
            }
            document.getElementsByTagName('head')[0].appendChild(s);
        } else {
            document.write('<style type="text/css">' + t + '</style>');
        }
    }

    function getVendorPropertyName(prop) {

        var prefixes = ['Moz', 'Webkit', 'O', 'ms'],
            vendorProp, i,
            div = document.createElement('div'),
            prop_ = prop.charAt(0).toUpperCase() + prop.substr(1);

        if (prop in div.style) {
            return prop;
        }

        for (i = 0; i < prefixes.length; ++i) {

            vendorProp = prefixes[i] + prop_;

            if (vendorProp in div.style) {
                return vendorProp;
            }

        }

        // Avoid memory leak in IE.
        this.div = null;
    };

    function deparam(query) {
        var pairs, i, keyValuePair, key, value, map = {};
        // remove leading question mark if its there
        if (query.slice(0, 1) === '?') {
            query = query.slice(1);
        }
        if (query !== '') {
            pairs = query.split('&');
            for (i = 0; i < pairs.length; i += 1) {
                keyValuePair = pairs[i].split('=');
                key = decodeURIComponent(keyValuePair[0]);
                value = (keyValuePair.length > 1) ? decodeURIComponent(keyValuePair[1]) : undefined;
                map[key] = value;
            }
        }
        return map;
    }


})(window.jQuery);