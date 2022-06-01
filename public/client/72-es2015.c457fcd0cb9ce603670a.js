"use strict";(self.webpackChunkbedesk=self.webpackChunkbedesk||[]).push([[72],{21072:function(t,n,e){e.r(n),e.d(n,{NotificationsModule:function(){return z}});var i=e(38583),o=e(24668),s=e(26215),a=e(45435),r=e(68939),c=e(3679),l=e(37716),u=e(90299);let g=(()=>{class t{constructor(t){this.http=t}getAll(t){return this.http.get(`notifications/${t}/subscriptions`)}updateUserSubscriptions(t,n){return this.http.put(`notifications/${t}/subscriptions`,{selections:n})}}return t.\u0275fac=function(n){return new(n||t)(l.LFG(u.r))},t.\u0275prov=l.Yz7({token:t,factory:t.\u0275fac,providedIn:"root"}),t})();var d=e(24688),p=e(35718),h=e(34673),f=e(70837),m=e(51095),b=e(15515),v=e(7539);function x(t,n){if(1&t){const t=l.EpF();l.TgZ(0,"div",12),l.TgZ(1,"div",13),l._uU(2),l.qZA(),l.TgZ(3,"mat-checkbox",14),l.NdJ("change",function(n){const e=l.CHM(t).$implicit,i=l.oxw(3);return n?i.toggleAllRowsFor(e):null}),l.qZA(),l.qZA()}if(2&t){const t=n.$implicit,e=l.oxw(3);l.xp6(2),l.Oqu(t),l.xp6(1),l.Q6J("checked",e.allRowsSelectedFor(t))("indeterminate",e.someRowsSelectedFor(t)&&!e.allRowsSelectedFor(t))("disabled","browser"===t&&!e.supportsBrowserNotifications)}}function P(t,n){if(1&t&&(l.ynx(0),l.YNc(1,x,4,4,"div",11),l.BQk()),2&t){const t=l.oxw(2);l.xp6(1),l.Q6J("ngForOf",t.availableChannels)}}function w(t,n){if(1&t&&(l.TgZ(0,"div",12),l._UZ(1,"mat-checkbox",17),l.qZA()),2&t){const t=n.$implicit;l.xp6(1),l.Q6J("formControlName",t)}}function Z(t,n){if(1&t&&(l.TgZ(0,"div",15),l.TgZ(1,"div",16),l._uU(2),l.qZA(),l.YNc(3,w,2,1,"div",11),l.qZA()),2&t){const t=n.$implicit,e=n.last,i=l.oxw(2);l.ekj("no-border",e),l.Q6J("formGroupName",t.notif_id),l.xp6(2),l.Oqu(t.name),l.xp6(1),l.Q6J("ngForOf",i.availableChannels)}}function O(t,n){if(1&t&&(l.TgZ(0,"div",5),l.TgZ(1,"div",6),l.TgZ(2,"div",7),l._uU(3),l.qZA(),l.YNc(4,P,2,1,"ng-container",8),l.qZA(),l.TgZ(5,"div",9),l.YNc(6,Z,4,5,"div",10),l.qZA(),l.qZA()),2&t){const t=n.$implicit,e=n.first,i=l.oxw();l.xp6(3),l.Oqu(t.group_name),l.xp6(1),l.Q6J("ngIf",e),l.xp6(1),l.Q6J("formGroup",i.form),l.xp6(1),l.Q6J("ngForOf",t.subscriptions)}}let C=(()=>{class t{constructor(t,n,e,i,o,a){this.route=t,this.api=n,this.currentUser=e,this.toast=i,this.cd=o,this.settings=a,this.availableChannels=[],this.loading$=new s.X(!1),this.supportsBrowserNotifications="Notification"in window,this.form=new c.cw({})}ngOnInit(){this.route.data.subscribe(t=>{this.groupedNotifications=t.api.subscriptions,this.availableChannels=t.api.available_channels,this.initialUserSelections=t.api.user_selections,this.groupedNotifications.forEach(t=>{t.subscriptions.forEach(t=>this.addNotifFormField(t.notif_id))})})}addNotifFormField(t){const n=new c.cw({});this.availableChannels.forEach(e=>{var i;const o=null===(i=this.initialUserSelections.find(n=>n.notif_id===t))||void 0===i?void 0:i.channels[e],s=new c.NI({value:null!=o&&o,disabled:"browser"===e&&!this.supportsBrowserNotifications});"browser"===e&&"granted"!==Notification.permission&&s.valueChanges.pipe((0,a.h)(t=>t)).subscribe(t=>{"denied"===Notification.permission?(this.toast.open("Notifications blocked. Please enable them for this site from browser settings."),s.setValue(!1,{emitEvent:!1}),this.cd.markForCheck()):Notification.requestPermission().then(t=>{"granted"!==t&&(s.setValue(!1,{emitEvent:!1}),this.cd.markForCheck())})}),n.addControl(e,s)}),this.form.addControl(t,n)}toggleAllRowsFor(t){const n=!this.allRowsSelectedFor(t);Object.values(this.form.controls).forEach(e=>{e.controls[t].setValue(n),e.controls[t].markAsDirty()})}allRowsSelectedFor(t){return Object.values(this.form.controls).every(n=>!0===n.controls[t].value)}someRowsSelectedFor(t){return Object.values(this.form.controls).some(n=>!0===n.controls[t].value)}saveSettings(){this.loading$.next(!0);const t=this.getPayload();this.api.updateUserSubscriptions(this.currentUser.get("id"),t).pipe((0,r.x)(()=>this.loading$.next(!1))).subscribe(()=>{this.form.markAsPristine(),this.toast.open("Notification settings updated.")})}getPayload(){return Object.entries(this.form.controls).filter(([t,n])=>n.dirty).map(([t,n])=>({notif_id:t,channels:n.value}))}}return t.\u0275fac=function(n){return new(n||t)(l.Y36(o.gz),l.Y36(g),l.Y36(d.I),l.Y36(p.F),l.Y36(l.sBO),l.Y36(h.Z))},t.\u0275cmp=l.Xpm({type:t,selectors:[["notification-subscriptions"]],decls:7,vars:5,consts:[[1,"box-shadow",3,"menuPosition"],[1,"be-container"],[1,"table","material-panel",3,"ngSubmit"],["class","setting-group",4,"ngFor","ngForOf"],["mat-raised-button","","color","accent","trans","",1,"submit-button",3,"disabled"],[1,"setting-group"],[1,"row"],["trans","",1,"name-column","strong"],[4,"ngIf"],[3,"formGroup"],["class","row indent",3,"no-border","formGroupName",4,"ngFor","ngForOf"],["class","channel-column",4,"ngFor","ngForOf"],[1,"channel-column"],["trans","",1,"channel-name"],[3,"checked","indeterminate","disabled","change"],[1,"row","indent",3,"formGroupName"],["trans","",1,"name-column"],[3,"formControlName"]],template:function(t,n){1&t&&(l._UZ(0,"material-navbar",0),l.TgZ(1,"div",1),l.TgZ(2,"form",2),l.NdJ("ngSubmit",function(){return n.saveSettings()}),l.YNc(3,O,7,4,"div",3),l.TgZ(4,"button",4),l.ALo(5,"async"),l._uU(6," Save Settings "),l.qZA(),l.qZA(),l.qZA()),2&t&&(l.Q6J("menuPosition",n.settings.get("vebto.navbar.defaultPosition")),l.xp6(3),l.Q6J("ngForOf",n.groupedNotifications),l.xp6(1),l.Q6J("disabled",l.lcZ(5,3,n.loading$)))},directives:[f.e,c._Y,c.JL,c.F,i.sg,m.lW,b.P,i.O5,c.sg,v.oG,c.x0,c.JJ,c.u],pipes:[i.Ov],styles:["[_nghost-%COMP%]{display:block;background-color:var(--be-background-alternative);min-height:100vh}.be-container[_ngcontent-%COMP%]{padding-top:35px;padding-bottom:35px}.table[_ngcontent-%COMP%]{border-radius:4px}.setting-group[_ngcontent-%COMP%]{margin-bottom:10px}.row[_ngcontent-%COMP%]{display:flex;align-items:center;border-bottom:1px solid var(--be-divider-default);padding:10px}.row.no-border[_ngcontent-%COMP%]{border-bottom:none}.row.indent[_ngcontent-%COMP%]{padding-left:20px}.name-column[_ngcontent-%COMP%]{flex:1 1 auto}.strong[_ngcontent-%COMP%]{font-weight:500;font-size:1.5rem;align-self:flex-end}.channel-name[_ngcontent-%COMP%]{margin-bottom:10px}.channel-column[_ngcontent-%COMP%]{width:75px;text-align:center;text-transform:capitalize}.submit-button[_ngcontent-%COMP%]{margin-top:15px}"],changeDetection:0}),t})();var _=e(5304),k=e(19773),A=e(59193),y=e(25917);let F=(()=>{class t{constructor(t,n,e){this.router=t,this.subscriptions=n,this.currentUser=e}resolve(t,n){return this.subscriptions.getAll(+this.currentUser.get("id")).pipe((0,_.K)(()=>(this.router.navigate(["/account/settings"]),A.E)),(0,k.zg)(t=>t?(0,y.of)(t):(this.router.navigate(["/account/settings"]),A.E)))}}return t.\u0275fac=function(n){return new(n||t)(l.LFG(o.F0),l.LFG(g),l.LFG(d.I))},t.\u0275prov=l.Yz7({token:t,factory:t.\u0275fac,providedIn:"root"}),t})();var N=e(99665),J=e(96605),q=e(31682);function M(t,n){if(1&t){const t=l.EpF();l.TgZ(0,"li"),l.TgZ(1,"button",4),l.NdJ("click",function(){const n=l.CHM(t).$implicit;return l.oxw().selectPage(n)}),l._uU(2),l.qZA(),l.qZA()}if(2&t){const t=n.$implicit,e=l.oxw();l.xp6(1),l.ekj("active",e.currentPage===t),l.Q6J("disabled",e.disabled),l.xp6(1),l.Oqu(t)}}let T=(()=>{class t{constructor(t){this.router=t,this.pageChanged=new l.vpe,this.disabled=!0}get shouldHide(){return this.numberOfPages<2}set pagination(t){t&&(this.numberOfPages=t.last_page>10?10:t.last_page,this.numberOfPages>1&&(this.iterator=Array.from(Array(this.numberOfPages).keys()).map(t=>t+1),this.currentPage=t.current_page))}selectPage(t){this.currentPage!==t&&(this.currentPage=t,this.pageChanged.next(t),this.router.navigate([],{queryParams:{page:t},replaceUrl:!0}))}nextPage(){const t=this.currentPage+1;this.selectPage(t<=this.numberOfPages?t:this.currentPage)}prevPage(){const t=this.currentPage-1;this.selectPage(t>=1?t:this.currentPage)}}return t.\u0275fac=function(n){return new(n||t)(l.Y36(o.F0))},t.\u0275cmp=l.Xpm({type:t,selectors:[["pagination-widget"]],hostVars:2,hostBindings:function(t,n){2&t&&l.ekj("hidden",n.shouldHide)},inputs:{disabled:"disabled",pagination:"pagination"},outputs:{pageChanged:"pageChanged"},decls:8,vars:3,consts:[[1,"page-numbers","unstyled-list"],["type","button","mat-button","","trans","",1,"prev",3,"disabled","click"],[4,"ngFor","ngForOf"],["type","button","mat-button","","trans","",1,"next",3,"disabled","click"],["type","button","mat-flat-button","","color","gray",1,"page-number-button",3,"disabled","click"]],template:function(t,n){1&t&&(l.TgZ(0,"ul",0),l.TgZ(1,"li"),l.TgZ(2,"button",1),l.NdJ("click",function(){return n.prevPage()}),l._uU(3,"Previous"),l.qZA(),l.qZA(),l.YNc(4,M,3,4,"li",2),l.TgZ(5,"li"),l.TgZ(6,"button",3),l.NdJ("click",function(){return n.nextPage()}),l._uU(7,"Next"),l.qZA(),l.qZA(),l.qZA()),2&t&&(l.xp6(2),l.Q6J("disabled",n.disabled),l.xp6(2),l.Q6J("ngForOf",n.iterator),l.xp6(2),l.Q6J("disabled",n.disabled))},directives:[m.lW,b.P,i.sg],styles:["[_nghost-%COMP%]{display:block}ul[_ngcontent-%COMP%]{display:flex;align-items:center;justify-content:center;flex-wrap:wrap;width:100%}li[_ngcontent-%COMP%]{margin:0 3px 6px}.page-number-button[_ngcontent-%COMP%]{width:40px;height:40px;min-width:40px;line-height:40px;padding:0}.active[_ngcontent-%COMP%]{background-color:var(--be-accent-default);color:var(--be-accent-contrast)}.next[_ngcontent-%COMP%], .prev[_ngcontent-%COMP%]{color:var(--be-accent-default)}"],changeDetection:0}),t})();const S=[{path:"",component:(()=>{class t{constructor(t,n,e,i){this.settings=t,this.notifications=n,this.breakpoints=e,this.route=i,this.pagination$=new s.X(null)}ngOnInit(){this.loadPage(this.route.snapshot.queryParams.page||1)}loadPage(t){this.notifications.load({page:t,perPage:25}).subscribe(t=>{this.pagination$.next(t.pagination)})}markAsRead(t){this.pagination$.value.data.find(n=>n.id===t.id).read_at=t.read_at}}return t.\u0275fac=function(n){return new(n||t)(l.Y36(h.Z),l.Y36(N.g),l.Y36(J.S),l.Y36(o.gz))},t.\u0275cmp=l.Xpm({type:t,selectors:[["notification-page"]],decls:8,vars:13,consts:[[3,"menuPosition"],[1,"be-container"],[3,"notifications","compact","markedAsRead"],[3,"pagination","disabled","pageChanged"]],template:function(t,n){if(1&t&&(l._UZ(0,"material-navbar",0),l.TgZ(1,"div",1),l.TgZ(2,"notification-list",2),l.NdJ("markedAsRead",function(t){return n.markAsRead(t)}),l.ALo(3,"async"),l.ALo(4,"async"),l.qZA(),l.TgZ(5,"pagination-widget",3),l.NdJ("pageChanged",function(t){return n.loadPage(t)}),l.ALo(6,"async"),l.ALo(7,"async"),l.qZA(),l.qZA()),2&t){let t;l.Q6J("menuPosition",n.settings.get("vebto.navbar.defaultPosition")),l.xp6(2),l.Q6J("notifications",null==(t=l.lcZ(3,5,n.pagination$))?null:t.data)("compact",l.lcZ(4,7,n.breakpoints.isMobile$)),l.xp6(3),l.Q6J("pagination",l.lcZ(6,9,n.pagination$))("disabled",l.lcZ(7,11,n.notifications.loading$))}},directives:[f.e,q.Z,T],pipes:[i.Ov],styles:["[_nghost-%COMP%]{display:block;min-height:100vh;background-color:var(--be-background-alternative)}.be-container[_ngcontent-%COMP%]{padding-top:25px;padding-bottom:25px}pagination-widget[_ngcontent-%COMP%]{margin-top:35px}"],changeDetection:0}),t})()},{path:"settings",component:C,resolve:{api:F},data:{permissions:["notification.subscribe"]}}];let U=(()=>{class t{}return t.\u0275fac=function(n){return new(n||t)},t.\u0275mod=l.oAB({type:t}),t.\u0275inj=l.cJS({imports:[[o.Bz.forChild(S)],o.Bz]}),t})();var Y=e(68285),$=e(40744),Q=e(18295),R=e(49866);let j=(()=>{class t{}return t.\u0275fac=function(n){return new(n||t)},t.\u0275mod=l.oAB({type:t}),t.\u0275inj=l.cJS({imports:[[i.ez,m.ot,R.N]]}),t})(),z=(()=>{class t{}return t.\u0275fac=function(n){return new(n||t)},t.\u0275mod=l.oAB({type:t}),t.\u0275inj=l.cJS({imports:[[i.ez,c.u5,c.UX,U,Q.l,Y.T,$.m,j,R.N,v.p9,m.ot]]}),t})()}}]);