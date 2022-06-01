"use strict";(self.webpackChunkbedesk=self.webpackChunkbedesk||[]).push([[476],{85476:function(t,e,n){n.r(e),n.d(e,{CustomerMailboxModule:function(){return Jt}});var i=n(38583),o=n(3679),s=n(24668),r=n(65987),a=n(37716),c=n(24688),l=n(15515),u=n(51095),g=n(3028),d=n(1303),p=n(20616),h=n(58341),m=n(76403),f=n(32141),b=n(18256);function x(t,e){if(1&t&&(a.TgZ(0,"mat-chip-list",32),a.TgZ(1,"mat-chip",33),a._uU(2),a.qZA(),a.qZA()),2&t){const t=e.ngIf,n=a.oxw().$implicit;a.Q6J("selectable",!1),a.xp6(1),a.Q6J("color",n.closed_at?"standard":"success"),a.xp6(1),a.Oqu(t.display_name)}}function Z(t,e){if(1&t){const t=a.EpF();a.TgZ(0,"tr",25),a.NdJ("click",function(){const e=a.CHM(t).$implicit;return a.oxw().openConversation(e)}),a.TgZ(1,"td",26),a._uU(2),a.qZA(),a.TgZ(3,"td",27),a._uU(4),a.qZA(),a.TgZ(5,"td",28),a._uU(6),a.ALo(7,"formattedDate"),a.qZA(),a.TgZ(8,"td",29),a._uU(9),a.qZA(),a.TgZ(10,"td",30),a.YNc(11,x,3,3,"mat-chip-list",31),a.qZA(),a.qZA()}if(2&t){const t=e.$implicit,n=a.oxw();a.ekj("open",!t.closed_at),a.xp6(2),a.Oqu(t.subject),a.xp6(2),a.hij("#",t.id,""),a.xp6(2),a.Oqu(a.lcZ(7,7,t.created_at)),a.xp6(3),a.Oqu(t.updated_at_formatted),a.xp6(2),a.Q6J("ngIf",n.getStatus(t))}}function v(t,e){1&t&&(a.TgZ(0,"no-results-message",34),a.TgZ(1,"span",35),a._uU(2,"You have not created any tickets yet."),a.qZA(),a.qZA())}function k(t,e){1&t&&a._UZ(0,"datatable-footer",36),2&t&&a.Q6J("hidePerPage",!0)}let w=(()=>{class t{constructor(t,e,n,i){this.currentUser=t,this.router=e,this.route=n,this.datatable=i,this.tickets$=this.datatable.data$,this.statusFormControl=new o.NI(this.route.snapshot.queryParams.status||"")}ngOnInit(){this.datatable.init({uri:"tickets",staticParams:{userId:this.currentUser.get("id")}}),this.statusFormControl.valueChanges.subscribe(t=>{this.datatable.addFilter("tagId",t)})}ngOnDestroy(){this.datatable.destroy()}getStatus(t){return t.tags.filter(t=>"status"===t.type)[0]}openConversation(t){this.router.navigate(["/help-center/tickets",t.id])}}return t.\u0275fac=function(e){return new(e||t)(a.Y36(c.I),a.Y36(s.F0),a.Y36(s.gz),a.Y36(r.x))},t.\u0275cmp=a.Xpm({type:t,selectors:[["customer-tickets-list"]],features:[a._Bn([r.x])],decls:44,vars:13,consts:[[1,"container"],[1,"title"],["trans",""],["routerLink","/help-center/tickets/new","mat-raised-button","","color","accent","trans",""],[1,"filter-form"],[1,"input-container"],[1,"input-with-icon"],["placeholder","Search requests...",1,"icon-left"],[1,"input-container","status-input-container"],["for","customer-ticket-status","trans","",1,"hidden"],["id","customer-ticket-status",1,"filter-input",3,"formControl"],["value","","trans",""],["value","open","trans",""],["value","closed","trans",""],["value","pending","trans",""],[1,"material-table","responsive-table"],["trans","",1,"subject-column"],["table-sort-header","","trans","",1,"id-column"],["table-sort-header","created_at","trans","",1,"created-column"],["table-sort-header","updated_at","trans","",1,"last-updated-column"],["trans","",1,"status-column"],[3,"open","click",4,"ngFor","ngForOf"],["svgImage","opened.svg",4,"ngIf"],[3,"hidePerPage",4,"ngIf"],[1,"overlay",3,"isVisible"],[3,"click"],[1,"subject-column","mobile-no-label"],["data-label","ID",1,"id-column"],["data-label","Created",1,"created-column"],["data-label","Last Updated",1,"last-updated-column"],["data-label","Status",1,"status-column"],[3,"selectable",4,"ngIf"],[3,"selectable"],["selected","","trans","",3,"color"],["svgImage","opened.svg"],["primary-text",""],[3,"hidePerPage"]],template:function(t,e){if(1&t&&(a.TgZ(0,"div",0),a.TgZ(1,"div",1),a.TgZ(2,"h1",2),a._uU(3,"My requests"),a.qZA(),a.TgZ(4,"a",3),a._uU(5,"New Request"),a.qZA(),a.qZA(),a.TgZ(6,"form",4),a.TgZ(7,"div",5),a.TgZ(8,"div",6),a._UZ(9,"datatable-header-search-input",7),a.qZA(),a.qZA(),a.TgZ(10,"div",8),a.TgZ(11,"label",9),a._uU(12,"Status"),a.qZA(),a.TgZ(13,"select",10),a.TgZ(14,"option",11),a._uU(15,"All Requests"),a.qZA(),a.TgZ(16,"option",12),a._uU(17,"Open Requests"),a.qZA(),a.TgZ(18,"option",13),a._uU(19,"Closed Requests"),a.qZA(),a.TgZ(20,"option",14),a._uU(21,"Awaiting your reply"),a.qZA(),a.qZA(),a.qZA(),a.qZA(),a.TgZ(22,"table",15),a.TgZ(23,"thead"),a.TgZ(24,"tr"),a.TgZ(25,"th",16),a._uU(26,"Subject"),a.qZA(),a.TgZ(27,"th",17),a._uU(28,"ID"),a.qZA(),a.TgZ(29,"th",18),a._uU(30,"Created"),a.qZA(),a.TgZ(31,"th",19),a._uU(32,"Last Updated"),a.qZA(),a.TgZ(33,"th",20),a._uU(34,"Status"),a.qZA(),a.qZA(),a.qZA(),a.TgZ(35,"tbody"),a.YNc(36,Z,12,9,"tr",21),a.ALo(37,"async"),a.qZA(),a.qZA(),a.YNc(38,v,3,0,"no-results-message",22),a.ALo(39,"async"),a.YNc(40,k,1,1,"datatable-footer",23),a.ALo(41,"async"),a.qZA(),a._UZ(42,"loading-indicator",24),a.ALo(43,"async")),2&t){let t;a.xp6(13),a.Q6J("formControl",e.statusFormControl),a.xp6(23),a.Q6J("ngForOf",a.lcZ(37,5,e.tickets$)),a.xp6(2),a.Q6J("ngIf",a.lcZ(39,7,e.datatable.paginator.noResults$)),a.xp6(2),a.Q6J("ngIf",(null==(t=a.lcZ(41,9,e.datatable.paginator.response$))?null:t.pagination.total)>15),a.xp6(2),a.Q6J("isVisible",a.lcZ(43,11,e.datatable.paginator.loading$))}},directives:[l.P,s.yS,u.zs,o._Y,o.JL,o.F,g.w,o.EJ,o.JJ,o.oH,o.YN,o.Kr,d.p,i.sg,i.O5,p.Q,h.qn,h.HS,m.U,f.V],pipes:[i.Ov,b.l],styles:["[_nghost-%COMP%]{display:block;width:100%;padding:30px 0 50px}.title[_ngcontent-%COMP%]{display:flex;align-items:center;margin-bottom:30px;padding:0 20px}.title[_ngcontent-%COMP%]   h1[_ngcontent-%COMP%]{font-size:3.2rem;font-weight:400;margin:0}.title[_ngcontent-%COMP%]   a[_ngcontent-%COMP%]{margin-left:auto;height:36px}.filter-form[_ngcontent-%COMP%]{display:flex;align-items:center;margin-bottom:35px;padding:0 10px}.status-input-container[_ngcontent-%COMP%]{max-width:200px;margin-left:15px}datatable-header-search-input[_ngcontent-%COMP%]     input{height:50px;padding-left:40px}table[_ngcontent-%COMP%]{table-layout:fixed}tbody[_ngcontent-%COMP%]   tr[_ngcontent-%COMP%]{height:60px;cursor:pointer}tbody[_ngcontent-%COMP%]   tr[_ngcontent-%COMP%]:hover{background-color:var(--be-accent-emphasis)}.subject-column[_ngcontent-%COMP%]{white-space:nowrap;overflow:hidden;text-overflow:ellipsis;padding-right:25px}.id-column[_ngcontent-%COMP%], .status-column[_ngcontent-%COMP%]{width:110px}.created-column[_ngcontent-%COMP%], .last-updated-column[_ngcontent-%COMP%]{width:160px}.mat-chip[_ngcontent-%COMP%]{min-width:63px;justify-content:center;text-transform:capitalize}.mat-chip.mat-success[_ngcontent-%COMP%]{background-color:#43a047;color:#fff}.no-results-message[_ngcontent-%COMP%]{margin-top:60px;margin-bottom:60px}"]}),t})();var y=n(69958),A=n(82192),_=n(9141),q=n(26215),T=n(82298),C=n(68939),O=n(54395),M=n(87519),U=n(25917),I=n(76827),J=n(4928),P=n(76627),Q=n(75811);function Y(t,e){if(1&t&&(a.TgZ(0,"a",8),a._UZ(1,"mat-icon",9),a.TgZ(2,"span",10),a._uU(3),a.qZA(),a.qZA()),2&t){const t=e.$implicit,n=a.oxw();a.Q6J("routerLink",n.urls.article(t)),a.xp6(3),a.Oqu(t.title)}}let N=(()=>{class t{constructor(t,e){this.helpCenter=t,this.urls=e,this.valueChanged=new a.vpe,this.searchQuery=new o.NI,this.articles=[],this.searching=!1,this.suggestionLog=[]}ngOnInit(){this.searchQuery.valueChanges.subscribe(t=>{this.valueChanged.emit(t)}),this.searchQuery.valueChanges.pipe((0,O.b)(400),(0,M.x)()).subscribe(t=>{this.searching=!0,this.searchArticles(t).pipe((0,C.x)(()=>{this.searching=!1})).subscribe(e=>{this.setSearchResults(t,e.pagination.data)})})}searchArticles(t){return t?this.helpCenter.findArticles({query:t,perPage:4,categories:this.categories}):(0,U.of)({pagination:{data:[]}})}setSearchResults(t,e){if(e&&e.length){const n=Date.now();this.articles=e,this.suggestionLog.push({term:t,results:e.map(t=>t.id),timestampMs:n})}else setTimeout(()=>{this.articles=[]},300)}}return t.\u0275fac=function(e){return new(e||t)(a.Y36(I.x),a.Y36(J.X))},t.\u0275cmp=a.Xpm({type:t,selectors:[["suggested-articles-drawer"]],inputs:{placeholder:"placeholder",categories:"categories"},outputs:{valueChanged:"valueChanged"},decls:9,vars:7,consts:[[1,"suggestions-container"],[1,"input-container"],["type","text",1,"search-input",3,"formControl"],["svgIcon","search",1,"search-icon","input-icon"],[1,"results-container"],["trans","",1,"results-header"],["customScrollbar","",1,"results"],["class","result","target","_blank",3,"routerLink",4,"ngFor","ngForOf"],["target","_blank",1,"result",3,"routerLink"],["svgIcon","description"],[1,"title"]],template:function(t,e){1&t&&(a.TgZ(0,"div",0),a.TgZ(1,"div",1),a._UZ(2,"input",2),a._UZ(3,"mat-icon",3),a.qZA(),a.TgZ(4,"section",4),a.TgZ(5,"div",5),a._uU(6,"Were you looking for:"),a.qZA(),a.TgZ(7,"div",6),a.YNc(8,Y,4,2,"a",7),a.qZA(),a.qZA(),a.qZA()),2&t&&(a.ekj("has-results",e.articles.length),a.xp6(2),a.Q6J("formControl",e.searchQuery),a.uIk("placeholder",e.placeholder),a.xp6(1),a.ekj("searching",e.searching),a.xp6(5),a.Q6J("ngForOf",e.articles))},directives:[o.Fj,o.JJ,o.oH,P.Hw,l.P,Q.j,i.sg,s.yS],styles:["suggested-articles-drawer{display:block}suggested-articles-drawer .results-container{max-height:0;transition:all .3s ease-out;margin:0;overflow:hidden}suggested-articles-drawer .results-container>.results-header{font-size:2.1rem;font-weight:300;margin-bottom:20px}suggested-articles-drawer .results-container>.results>.result{display:flex;align-items:center;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;margin-bottom:12px;width:100%;color:var(--be-link);cursor:pointer}suggested-articles-drawer .results-container>.results>.result:hover{text-decoration:underline}suggested-articles-drawer .results-container>.results>.result>mat-icon{margin-right:10px}suggested-articles-drawer .results-container>.results>.result>.title{color:inherit}suggested-articles-drawer .suggestions-container .input-container{position:relative;width:100%}suggested-articles-drawer .suggestions-container .input-container>.input-icon{position:absolute;visibility:hidden;top:13px;right:11px;color:var(--be-secondary-text)}suggested-articles-drawer .suggestions-container .input-container>.input-icon.searching{visibility:visible;-webkit-animation:flickerAnimation .5s infinite;animation:flickerAnimation .5s infinite}@-webkit-keyframes flickerAnimation{0%{opacity:1}50%{opacity:.4}to{opacity:1}}@keyframes flickerAnimation{0%{opacity:1}50%{opacity:.4}to{opacity:1}}suggested-articles-drawer .suggestions-container.has-results .results-container{max-height:230px;margin:20px 0;transition:all .3s ease-in}\n"],encapsulation:2}),t})();var L=n(34673),$=n(97312),F=n(35718),j=n(87036),S=n(48350),H=n(91241);function z(t,e){if(1&t&&(a.TgZ(0,"option",28),a._uU(1),a.qZA()),2&t){const t=e.$implicit;a.Q6J("ngValue",t),a.xp6(1),a.hij(" ",t.display_name," ")}}function X(t,e){1&t&&(a.TgZ(0,"p",29),a.TgZ(1,"span",9),a._uU(2,"Your support for this item has expired."),a.qZA(),a._uU(3," "),a.TgZ(4,"a",30),a._uU(5,"Click here to renew."),a.qZA(),a.qZA())}function V(t,e){if(1&t&&(a.TgZ(0,"div",31),a._uU(1),a.qZA()),2&t){const t=a.oxw().ngIf;a.xp6(1),a.hij(" ",t.category||t.purchase_code," ")}}function R(t,e){if(1&t&&(a.TgZ(0,"div",32),a._uU(1),a.qZA()),2&t){const t=a.oxw().ngIf;a.xp6(1),a.Oqu(t.subject)}}function B(t,e){if(1&t&&(a.TgZ(0,"div",33),a._uU(1),a.qZA()),2&t){const t=a.oxw().ngIf;a.xp6(1),a.Oqu(t.body)}}function D(t,e){if(1&t){const t=a.EpF();a.TgZ(0,"form",8),a.NdJ("ngSubmit",function(){return a.CHM(t),a.oxw(2).createTicket()}),a.TgZ(1,"h1",9),a._uU(2),a.qZA(),a.TgZ(3,"div",10),a.TgZ(4,"label",11),a._uU(5),a.qZA(),a.TgZ(6,"select",12),a.NdJ("ngModelChange",function(e){return a.CHM(t),a.oxw(2).ticketModel.category=e}),a.TgZ(7,"option",13),a._uU(8,"-"),a.qZA(),a.YNc(9,z,2,2,"option",14),a.ALo(10,"async"),a.qZA(),a.YNc(11,X,6,0,"p",15),a.YNc(12,V,2,1,"div",16),a.qZA(),a.TgZ(13,"div",17),a.TgZ(14,"label",18),a._uU(15),a.qZA(),a.TgZ(16,"suggested-articles-drawer",19),a.NdJ("valueChanged",function(e){return a.CHM(t),a.oxw(2).ticketModel.subject=e}),a.qZA(),a.YNc(17,R,2,1,"div",20),a.qZA(),a.TgZ(18,"div",21),a.TgZ(19,"label",22),a._uU(20),a.qZA(),a.TgZ(21,"text-editor",23),a.NdJ("onFileUpload",function(e){return a.CHM(t),a.oxw(2).uploadFiles(e)})("onChange",function(e){return a.CHM(t),a.oxw(2).ticketModel.body=e}),a.qZA(),a._UZ(22,"upload-progress-bar"),a.YNc(23,B,2,1,"div",24),a.qZA(),a.TgZ(24,"reply-attachment-list",25),a.NdJ("detached",function(e){return a.CHM(t),a.oxw(2).removeAttachment(e)}),a.ALo(25,"async"),a.qZA(),a.TgZ(26,"div",26),a.TgZ(27,"button",27),a._uU(28),a.qZA(),a.qZA(),a.qZA()}if(2&t){const t=e.ngIf,n=a.oxw().ngIf,i=a.oxw();a.xp6(2),a.Oqu(n.title),a.xp6(3),a.Oqu(n.categoryLabel),a.xp6(1),a.Q6J("ngModel",i.ticketModel.category),a.xp6(1),a.Q6J("value",null),a.xp6(2),a.Q6J("ngForOf",a.lcZ(10,16,i.ticketCategories$)),a.xp6(2),a.Q6J("ngIf",null==i.ticketModel.category?null:i.ticketModel.category.support_expired),a.xp6(1),a.Q6J("ngIf",t.category||t.purchase_code),a.xp6(3),a.Oqu(n.subjectLabel),a.xp6(1),a.Q6J("categories",i.getArticleCategories()),a.xp6(1),a.Q6J("ngIf",t.subject),a.xp6(3),a.Oqu(n.descriptionLabel),a.xp6(1),a.Q6J("minHeight",230),a.xp6(2),a.Q6J("ngIf",t.body),a.xp6(1),a.Q6J("attachments",a.lcZ(25,18,i.attachments$)),a.xp6(3),a.Q6J("disabled",null==i.ticketModel.category?null:i.ticketModel.category.support_expired),a.xp6(1),a.hij(" ",n.submitButtonText," ")}}function E(t,e){if(1&t&&(a.TgZ(0,"div",34),a.TgZ(1,"div",35),a._uU(2),a.qZA(),a.TgZ(3,"div",36),a._uU(4),a.qZA(),a.qZA()),2&t){const t=e.$implicit;a.xp6(2),a.Oqu(t.title),a.xp6(2),a.Oqu(t.content)}}function G(t,e){if(1&t&&(a.ynx(0),a.TgZ(1,"div",2),a.YNc(2,D,29,20,"form",3),a.ALo(3,"async"),a.qZA(),a.TgZ(4,"div",4),a.TgZ(5,"div",5),a._uU(6),a.qZA(),a.TgZ(7,"div",6),a.YNc(8,E,5,2,"div",7),a.qZA(),a.qZA(),a.BQk()),2&t){const t=e.ngIf,n=a.oxw();a.xp6(2),a.Q6J("ngIf",a.lcZ(3,3,n.errors$)),a.xp6(4),a.Oqu(t.sidebarTitle),a.xp6(2),a.Q6J("ngForOf",t.sidebarTips)}}let W=(()=>{class t{constructor(t,e,n,i,o,s,r,a,c){this.settings=t,this.tickets=e,this.uploads=n,this.router=i,this.urls=o,this.route=s,this.toast=r,this.uploadQueue=a,this.searchLogger=c,this.errors$=new q.X({}),this.loading$=new q.X(!1),this.attachments$=new q.X([]),this.ticketCategories$=new q.X([]),this.ticketModel={category:null},this.content$=new T.t(1),this.settings.all$().subscribe(()=>{this.content$.next(this.settings.getJson("hc.newTicket.appearance",{}))})}ngOnInit(){this.tickets.newTicketCategories().subscribe(t=>{this.ticketCategories$.next(t.tags)})}ngOnDestroy(){this.textEditor.destroyEditor()}createTicket(){this.loading$.next(!0);const t={subject:this.ticketModel.subject,body:this.ticketModel.body,category:this.ticketModel.category&&this.ticketModel.category.id,uploads:this.uploadQueue.getAllCompleted().map(t=>t.id),suggestionLog:this.suggestions.suggestionLog};this.tickets.create(t).pipe((0,C.x)(()=>{this.loading$.next(!1)})).subscribe(()=>{this.searchLogger.updateLastSearch({createdTicket:!0}),this.router.navigateByUrl(this.urls.customerTicketList()),this.toast.open("Your request was successfully submitted.")},t=>{this.errors$.next(t.errors)})}uploadFiles(t){this.uploadQueue.start(t).subscribe(t=>{this.attachments$.next([...this.attachments$.value,t.fileEntry])})}removeAttachment(t){const e=this.attachments$.value.slice();for(let n=0;n<e.length;n++)e[n].id===t.id&&e.splice(n,1);this.attachments$.next(e)}getArticleCategories(){return this.ticketModel.category?this.ticketModel.category.categories.map(t=>t.id):[]}}return t.\u0275fac=function(e){return new(e||t)(a.Y36(L.Z),a.Y36(y.g),a.Y36($.f),a.Y36(s.F0),a.Y36(J.X),a.Y36(s.gz),a.Y36(F.F),a.Y36(_.A),a.Y36(j.X))},t.\u0275cmp=a.Xpm({type:t,selectors:[["new-ticket"]],viewQuery:function(t,e){if(1&t&&(a.Gf(A.d,5),a.Gf(N,5)),2&t){let t;a.iGM(t=a.CRH())&&(e.textEditor=t.first),a.iGM(t=a.CRH())&&(e.suggestions=t.first)}},hostAttrs:["id","new-ticket"],features:[a._Bn([y.g,_.A])],decls:4,vars:6,consts:[[4,"ngIf"],[1,"overlay",3,"isVisible"],[1,"left-column"],["ngNativeValidate","",3,"ngSubmit",4,"ngIf"],[1,"right-column"],["trans","",1,"header"],[1,"tips"],["class","tip-container",4,"ngFor","ngForOf"],["ngNativeValidate","",3,"ngSubmit"],["trans",""],[1,"input-container","category-input-container"],["for","category-select","trans",""],["name","category","id","category-select",3,"ngModel","ngModelChange"],["disabled","",3,"value"],[3,"ngValue",4,"ngFor","ngForOf"],["class","error",4,"ngIf"],["class","errors category-error",4,"ngIf"],[1,"input-container","subject-input-container"],["for","subject","trans",""],["id","subject","trans-placeholder","",3,"categories","valueChanged"],["class","errors subject-error",4,"ngIf"],[1,"input-container","description-input-container"],["for","description","trans",""],["id","description","inlineUploadPrefix","ticket_images",3,"minHeight","onFileUpload","onChange"],["class","errors body-error",4,"ngIf"],[3,"attachments","detached"],[1,"buttons"],["mat-raised-button","","color","accent","type","submit",1,"submit-button",3,"disabled"],[3,"ngValue"],[1,"error"],["target","_blank","href","https://codecanyon.net/downloads","trans",""],[1,"errors","category-error"],[1,"errors","subject-error"],[1,"errors","body-error"],[1,"tip-container"],["trans","",1,"title"],["trans","",1,"content"]],template:function(t,e){1&t&&(a.YNc(0,G,9,5,"ng-container",0),a.ALo(1,"async"),a._UZ(2,"loading-indicator",1),a.ALo(3,"async")),2&t&&(a.Q6J("ngIf",a.lcZ(1,2,e.content$)),a.xp6(2),a.Q6J("isVisible",a.lcZ(3,4,e.loading$)))},directives:[i.O5,p.Q,l.P,i.sg,o.JL,o.F,o.EJ,o.JJ,o.On,o.YN,o.Kr,N,A.d,S.o,H.G,u.lW],pipes:[i.Ov],styles:["new-ticket{display:flex;flex-wrap:wrap-reverse;width:100%;max-width:1800px;margin:0 auto;border-top:1px solid var(--be-divider-lighter)}new-ticket .left-column{flex:0 0 70%;padding:45px 20px}new-ticket .left-column form{margin:0 auto;width:660px}new-ticket .right-column{flex:0 0 30%;border-left:1px solid var(--be-divider-lighter);padding:45px 30px}new-ticket .right-column .header{font-size:2.1rem;color:var(--be-accent-default);font-weight:500;margin-bottom:35px}new-ticket .right-column .tip-container{margin-bottom:30px;font-size:1.6rem}new-ticket .right-column .tip-container .title{font-weight:400}new-ticket .right-column .tip-container .content{font-weight:300}new-ticket .input-container{margin-bottom:25px}new-ticket .input-container .input-container{margin-bottom:0}new-ticket .input-container input,new-ticket .input-container select{height:50px}new-ticket .input-container label{font-size:1.6rem;font-weight:300;margin-bottom:5px}new-ticket .errors{margin-top:7px;color:#f44336}new-ticket h1{margin:0 0 25px;color:var(--be-help-center-title-text);font-weight:300;font-size:4rem}new-ticket .buttons{margin-bottom:15px}@media only screen and (max-width: 768px){new-ticket .left-column{flex:0 0 100%;padding-top:0;padding-bottom:0}new-ticket .left-column>form{width:auto}new-ticket .right-column{flex:0 0 100%;border-bottom:1px solid var(--be-divider-lighter);margin-bottom:10px;padding:20px 30px 0}}\n"],encapsulation:2,changeDetection:0}),t})();var K=n(70837),tt=n(95414),et=n(57871),nt=n(10829);const it=["class","customer-mailbox"];function ot(t,e){1&t&&a._UZ(0,"customer-footer")}let st=(()=>{class t{constructor(t){this.settings=t}}return t.\u0275fac=function(e){return new(e||t)(a.Y36(L.Z))},t.\u0275cmp=a.Xpm({type:t,selectors:[["customer-mailbox",8,"customer-mailbox"]],attrs:it,decls:5,vars:1,consts:[["menuPosition","header",1,"hc-content-navbar"],["placeholder","Search help center...",1,"nav-searchbar"],["resource","Tickets","resourceType","static",1,"container"],[4,"ngIf"]],template:function(t,e){1&t&&(a.TgZ(0,"material-navbar",0),a._UZ(1,"suggested-articles-dropdown",1),a.qZA(),a._UZ(2,"breadcrumbs",2),a._UZ(3,"router-outlet"),a.YNc(4,ot,1,0,"customer-footer",3)),2&t&&(a.xp6(4),a.Q6J("ngIf",!e.settings.get("hc.hide_footer")))},directives:[K.e,tt.B,et.t,s.lC,i.O5,nt.e],styles:["customer-mailbox{display:block;padding-top:70px}customer-mailbox breadcrumbs.container{margin:0 auto;padding:36px;font-size:1.6rem}customer-mailbox customer-conversation,customer-mailbox customer-tickets-list,customer-mailbox new-ticket{min-height:calc(100vh - 165px)}@media screen and (max-width: 768px){customer-mailbox breadcrumbs{display:none}}\n"],encapsulation:2}),t})();var rt=n(66010),at=n(87291),ct=n(19867),lt=n(30587),ut=n(35976),gt=n(61693);const dt=["scrollContainer"];function pt(t,e){if(1&t){const t=a.EpF();a.TgZ(0,"div",6),a.TgZ(1,"button",7),a.NdJ("click",function(){return a.CHM(t),a.oxw().markAsSolved()}),a._uU(2,"Mark as solved"),a.qZA(),a.qZA()}}function ht(t,e){1&t&&(a.TgZ(0,"div",8),a._uU(1," This ticket was locked due to inactivity. To reply, create a new ticket. "),a.qZA())}let mt=(()=>{class t{constructor(t,e,n,i,o,s){this.route=t,this.uploads=e,this.currentUser=n,this.conversation=i,this.tickets=o,this.tags=s}ngAfterViewInit(){this.conversation.scrollContainer=this.scrollContainer.nativeElement}ngOnInit(){this.route.data.subscribe(t=>{this.conversation.init(t.ticket)})}markAsSolved(){const t=this.tags.getTagByIdOrName("closed");this.tickets.changeTicketStatus(this.conversation.get().id,t.name).subscribe(()=>{this.conversation.afterReplyAction.navigateToCustomerTicketsList("Ticket marked as solved."),this.conversation.setStatus(t)})}}return t.\u0275fac=function(e){return new(e||t)(a.Y36(s.gz),a.Y36($.f),a.Y36(c.I),a.Y36(rt.r),a.Y36(y.g),a.Y36(at.N))},t.\u0275cmp=a.Xpm({type:t,selectors:[["customer-conversation"]],viewQuery:function(t,e){if(1&t&&a.Gf(dt,5),2&t){let t;a.iGM(t=a.CRH())&&(e.scrollContainer=t.first)}},decls:9,vars:4,consts:[[1,"container"],["class","resolve-btn-container",4,"ngIf"],["class","locked-info",4,"ngIf"],["conversationInfiniteScroll","",1,"ticket-container",3,"scrollTargetIsDocument"],["scrollContainer",""],[1,"overlay",3,"isVisible"],[1,"resolve-btn-container"],["mat-raised-button","","color","accent","trans","",3,"click"],[1,"locked-info"]],template:function(t,e){1&t&&(a.TgZ(0,"div",0),a.YNc(1,pt,3,0,"div",1),a.YNc(2,ht,2,0,"div",2),a.TgZ(3,"div",3,4),a._UZ(5,"conversation-header"),a._UZ(6,"conversation-text-editor"),a._UZ(7,"conversation-replies"),a.qZA(),a.qZA(),a._UZ(8,"loading-indicator",5)),2&t&&(a.xp6(1),a.Q6J("ngIf",!e.conversation.get().closed_at),a.xp6(1),a.Q6J("ngIf","locked"===e.conversation.get().status),a.xp6(1),a.Q6J("scrollTargetIsDocument",!0),a.xp6(5),a.Q6J("isVisible",e.conversation.replies.isLoading||e.conversation.isLoading))},directives:[i.O5,ct.b,lt.M,ut.B,gt.Q,p.Q,u.lW,l.P],styles:["[_nghost-%COMP%]{display:block;position:relative}.ticket-container[_ngcontent-%COMP%]{padding-bottom:50px}.resolve-btn-container[_ngcontent-%COMP%]{display:none;margin-bottom:40px;text-align:right}.locked-info[_ngcontent-%COMP%]{text-align:center;padding:16px;background-color:#fef7ed;border-left:5px solid #f3a12c;border-radius:4px;margin-bottom:25px}"]}),t})();var ft=n(20964),bt=n(42690);const xt=s.Bz.forChild([{path:"",component:st,canActivate:[bt.a],children:[{path:"",component:w},{path:"new",component:W,data:{permissions:["tickets.create"]}},{path:":id",component:mt,resolve:{ticket:ft.V}}]}]);var Zt=n(12245),vt=n(8574),kt=n(75892),wt=n(18295),yt=n(5259),At=n(49866),_t=n(98097),qt=n(43389),Tt=n(95217),Ct=n(16450),Ot=n(13202),Mt=n(88947),Ut=n(66705),It=n(58193);let Jt=(()=>{class t{}return t.\u0275fac=function(e){return new(e||t)},t.\u0275mod=a.oAB({type:t}),t.\u0275inj=a.cJS({imports:[[i.ez,o.u5,o.UX,s.Bz,vt.m,Zt.b,kt.t,yt.y,wt.l,At.N,qt.p,Tt.u,Ct.m,Ot.R,Mt.A,Ut.z,It.x,xt,P.Ps,h.Hi,u.ot,_t.X]]}),t})()},3028:function(t,e,n){n.d(e,{w:function(){return u}});var i=n(37716),o=n(65987),s=n(3679),r=n(38583),a=n(76627),c=n(63297);function l(t,e){1&t&&i._UZ(0,"mat-icon",3)}let u=(()=>{class t{constructor(t){this.datatable=t,this.hideIcon=!1}}return t.\u0275fac=function(e){return new(e||t)(i.Y36(o.x))},t.\u0275cmp=i.Xpm({type:t,selectors:[["datatable-header-search-input"]],inputs:{placeholder:"placeholder",hideIcon:"hideIcon"},decls:4,vars:5,consts:[[1,"input-container","search-input-container"],["type","text",1,"search-input",3,"placeholder","formControl"],["svgIcon","search",4,"ngIf"],["svgIcon","search"]],template:function(t,e){1&t&&(i.TgZ(0,"div",0),i._UZ(1,"input",1),i.ALo(2,"trans"),i.YNc(3,l,1,0,"mat-icon",2),i.qZA()),2&t&&(i.xp6(1),i.Q6J("placeholder",i.lcZ(2,3,e.placeholder))("formControl",e.datatable.searchControl),i.xp6(2),i.Q6J("ngIf",!e.hideIcon))},directives:[s.Fj,s.JJ,s.oH,r.O5,a.Hw],pipes:[c.R],styles:[".input-container[_ngcontent-%COMP%]{min-width:250px;position:relative;flex-shrink:0}.input-container[_ngcontent-%COMP%]   .mat-icon[_ngcontent-%COMP%]{position:absolute;top:calc(50% - 12px);right:5px;color:var(--be-secondary-text)}.icon-left[_nghost-%COMP%]   .mat-icon[_ngcontent-%COMP%], .icon-left   [_nghost-%COMP%]   .mat-icon[_ngcontent-%COMP%]{left:10px;right:0}"],changeDetection:0}),t})()}}]);