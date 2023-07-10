import{L as Z}from"./Loader-587f361e.js";import{P as ee}from"./PageTitle-d62c03d9.js";import{P as te}from"./Pagination-f12724ac.js";import{S as se}from"./Success-7008e664.js";import{U as _}from"./util-54581a61.js";import{_ as ae,r as u,o as c,c as h,b as m,w as p,a as e,d as H,t as r,g as ie,f as M,F as g,l as k,n as oe,x as re,y as le}from"./app-913bfa44.js";const ne={props:["uuid"],data(){return{isDetail:!1,isLoading:!1,jobVacancy:{},detailWorker:{},registerJobs:[],metaPagination:{},pagination:{perPage:10,page:1},filters:{search:""},village:"",district:"",regency:"",province:"",msg:""}},watch:{uuid(s){this.uuid=s}},mounted(){this.getJobVacancy(),this.getRegisterJobs()},methods:{getJobVacancy(){this.$store.dispatch("showData",["job-vacancy",this.uuid]).then(s=>{this.jobVacancy=s.data})},getRegisterJobs(){this.isLoading=!0;let s=[`per_page=${this.pagination.perPage}`,`page=${this.pagination.page}`,`search=${this.filters.search}`,`job_vacancy=${this.uuid}`].join("&");this.$store.dispatch("getData",["register-job",s]).then(a=>{this.isLoading=!1,this.registerJobs=a.data}).catch(a=>{this.isLoading=!1})},getVillage(s){if(this.isDetail)return fetch(`${this.$store.state.BASE_URL_REGION}village/${s}.json`).then(a=>a.json()).then(a=>{this.village=_.convertToCapitalize(a.name)}),this.village},getDistrict(s){if(this.isDetail)return fetch(`${this.$store.state.BASE_URL_REGION}district/${s}.json`).then(a=>a.json()).then(a=>{this.district=_.convertToCapitalize(a.name)}),this.district},getRegency(s){if(this.isDetail)return fetch(`${this.$store.state.BASE_URL_REGION}regency/${s}.json`).then(a=>a.json()).then(a=>{this.regency=_.convertToCapitalize(a.name)}),this.regency},getProvince(s){if(this.isDetail)return fetch(`${this.$store.state.BASE_URL_REGION}province/${s}.json`).then(a=>a.json()).then(a=>{this.province=_.convertToCapitalize(a.name)}),this.province},getCitizenship(s){return _.getCitizenship(s)},getSkill(s){return _.convertToCapitalize(s)},getStatus(s){return s==1?"Diterima":s==2?"Ditolak":"Pending"},handleAccept(s){this.isLoading=!0;let a={userId:s,jobVacancyId:this.uuid};this.$store.dispatch("postData",["register-job/accept",a]).then(b=>{this.isLoading=!1,$("#detailWorker").modal("hide"),$("#successModal").modal("show"),this.msg="lamaran berhasil diterima.",this.getRegisterJobs()}).catch(b=>{this.isLoading=!1})},handleReject(s){this.isLoading=!0;let a={userId:s,jobVacancyId:this.uuid};this.$store.dispatch("postData",["register-job/reject",a]).then(b=>{this.isLoading=!1,$("#detailWorker").modal("hide"),$("#successModal").modal("show"),this.msg="lamaran berhasil ditolak.",this.getRegisterJobs()}).catch(b=>{this.isLoading=!1})},onBack(){this.$router.back()}},components:{PageTitle:ee,Pagination:te,Loader:Z,Success:se}},i=s=>(re("data-v-52557e6d"),s=s(),le(),s),ce={class:"breadcrumb m-0"},de={class:"breadcrumb-item"},he={class:"breadcrumb-item"},_e=i(()=>e("span",null," Lowongan Pekerjaan ",-1)),ue=i(()=>e("li",{class:"breadcrumb-item active"},"Daftar Pelamar",-1)),me={class:"row"},be={class:"col-sm-12"},pe={class:"card bg-primary"},ge={class:"card-body profile-user-box"},ke={class:"row"},ve={class:"col-sm-8"},fe={class:"row align-items-center"},ye={class:"col"},De={class:"mt-1 mb-1 text-white"},We=i(()=>e("p",{class:"font-13 text-white-50"}," Lowongan Pekerjaan ",-1)),je={class:"mb-0 list-inline text-light"},we={class:"list-inline-item me-3"},Pe={class:"mb-1 text-white"},Le=i(()=>e("p",{class:"mb-0 font-13 text-white-50"}," Jenis Pekerjaan ",-1)),Se={class:"list-inline-item"},xe={class:"mb-1 text-white"},Ce=i(()=>e("p",{class:"mb-0 font-13 text-white-50"}," Penempatan Kerja ",-1)),Ve=i(()=>e("div",{class:"col-sm-4"},[e("div",{class:"text-center mt-sm-0 mt-3 text-sm-end"})],-1)),Re={class:"card position-relative"},Ne={class:"card-body"},Te={class:"table-responsive"},Be={class:"table table-hover table-bordered"},Ie=i(()=>e("thead",null,[e("tr",null,[e("th",null,"No."),e("th",null,"Nama Pelamar"),e("th",null,"No. Telepon"),e("th",null,"Status"),e("th",null,"Aksi")])],-1)),ze=i(()=>e("td",null,"1",-1)),Ee=["onClick"],Ae={class:"modal fade",id:"detailWorker",tabindex:"-1","aria-labelledby":"detailWorkerLabel","aria-hidden":"true","data-bs-backdrop":"static","data-bs-keyboard":"false"},Je={class:"modal-dialog modal-lg"},Ke={class:"modal-content"},Ue=i(()=>e("div",{class:"modal-header"},[e("h1",{class:"modal-title fs-5",id:"detailWorkerLabel"}," Detail Pelamar ")],-1)),Oe={class:"modal-body"},Ge={class:"row"},Fe={class:"col-md-3 mb-3"},Me=["src","alt"],He={class:"col-md-9"},qe={class:"table-responsive"},Qe={class:"table table-hover table-bordered"},Xe=i(()=>e("th",{class:"p-2 border bg-primary"}," Nama ",-1)),Ye={class:"p-2 border"},Ze=i(()=>e("th",{class:"p-2 border bg-primary"}," No. KTP ",-1)),$e={class:"p-2 border"},et=i(()=>e("th",{class:"p-2 border bg-primary"}," Tempat, Tanggal Lahir ",-1)),tt={class:"p-2 border"},st=i(()=>e("th",{class:"p-2 border bg-primary"}," Alamat ",-1)),at={class:"p-2 border"},it=i(()=>e("th",{class:"p-2 border bg-primary"}," Kewarganegaraan ",-1)),ot={class:"p-2 border"},rt=i(()=>e("th",{class:"p-2 border bg-primary"}," Pengalaman Kerja ",-1)),lt={class:"p-2 border"},nt=i(()=>e("th",{class:"p-2 border bg-primary"}," Keahlian Pelamar ",-1)),ct={class:"p-2 border"},dt=i(()=>e("th",{class:"p-2 border bg-primary"},[H(" Jawaban Pelamar "),e("br"),e("small",{class:"p-0 fw-normal"},"(terkait keunggulan pelamar)")],-1)),ht={class:"p-2 border"},_t=i(()=>e("th",{class:"p-2 border bg-primary"}," Dokumen Pendukung ",-1)),ut={class:"p-2 border"},mt=["href"],bt=["href"],pt={class:"modal-footer d-flex justify-content-between"},gt=["disabled"],kt={key:0},vt=["disabled"],ft=["disabled"];function yt(s,a,b,Dt,t,l){var f,y,D,W,j,w,P,L,S,x,C,V,R,N,T,B,I,z,E,A,J,K,U,O;const v=u("router-link"),q=u("PageTitle"),Q=u("Loader"),X=u("center"),Y=u("Success");return c(),h(g,null,[m(q,{title:"Daftar Pelamar",isBack:!0,onOnBack:a[0]||(a[0]=o=>l.onBack(o))},{default:p(()=>[e("ol",ce,[e("li",de,[m(v,{to:{name:"Dashboard"}},{default:p(()=>[H("Dashboard")]),_:1})]),e("li",he,[m(v,{to:{name:"Job Vacancy"}},{default:p(()=>[_e]),_:1})]),ue])]),_:1}),e("div",me,[e("div",be,[e("div",pe,[e("div",ge,[e("div",ke,[e("div",ve,[e("div",fe,[e("div",ye,[e("div",null,[e("h4",De,r((f=t.jobVacancy.welderSkill)==null?void 0:f.skillName),1),We,e("ul",je,[e("li",we,[e("h5",Pe,r(t.jobVacancy.workType),1),Le]),e("li",Se,[e("h5",xe,r(t.jobVacancy.placement),1),Ce])])])])])]),Ve])])])])]),e("div",Re,[t.isLoading?(c(),ie(Q,{key:0})):M("",!0),e("div",Ne,[e("div",Te,[e("table",Be,[Ie,e("tbody",null,[(c(!0),h(g,null,k(t.registerJobs,(o,n)=>{var d,G,F;return c(),h("tr",{key:n},[ze,e("td",null,r((d=o.user)==null?void 0:d.name),1),e("td",null,r((F=(G=o.user)==null?void 0:G.personalData)==null?void 0:F.phone),1),e("td",null,[e("span",{class:oe(["badge",o.status==1?"btn-success":o.status==2?"btn-danger":"btn-warning"])},r(l.getStatus(o.status)),3)]),e("td",null,[e("button",{class:"btn btn-sm btn-primary","data-bs-toggle":"modal","data-bs-target":"#detailWorker",onClick:Wt=>(t.detailWorker=o,t.isDetail=!0)}," Detail ",8,Ee)])])}),128))])])])])]),e("div",Ae,[e("div",Je,[e("div",Ke,[Ue,e("div",Oe,[e("div",Ge,[e("div",Fe,[m(X,null,{default:p(()=>{var o,n,d;return[e("img",{src:(n=(o=t.detailWorker.user)==null?void 0:o.detailWorker)==null?void 0:n.pasPhoto,alt:(d=t.detailWorker.user)==null?void 0:d.name,style:{width:"3cm",height:"4cm"}},null,8,Me)]}),_:1})]),e("div",He,[e("div",qe,[e("table",Qe,[e("tr",null,[Xe,e("td",Ye,r((y=t.detailWorker.user)==null?void 0:y.name),1)]),e("tr",null,[Ze,e("td",$e,r((W=(D=t.detailWorker.user)==null?void 0:D.detailWorker)==null?void 0:W.residentIdCard),1)]),e("tr",null,[et,e("td",tt,r(`${(w=(j=t.detailWorker.user)==null?void 0:j.detailWorker)==null?void 0:w.birthPlace}, ${(L=(P=t.detailWorker.user)==null?void 0:P.detailWorker)==null?void 0:L.dateBirth}`),1)]),e("tr",null,[st,e("td",at,r(`desa ${l.getVillage((x=(S=t.detailWorker.user)==null?void 0:S.personalData)==null?void 0:x.village)}, kec. ${l.getDistrict((V=(C=t.detailWorker.user)==null?void 0:C.personalData)==null?void 0:V.district)}, kab. ${l.getRegency((N=(R=t.detailWorker.user)==null?void 0:R.personalData)==null?void 0:N.regency)}, ${l.getProvince((B=(T=t.detailWorker.user)==null?void 0:T.personalData)==null?void 0:B.province)}, ${(z=(I=t.detailWorker.user)==null?void 0:I.personalData)==null?void 0:z.zipCode}`),1)]),e("tr",null,[it,e("td",ot,r(l.getCitizenship((A=(E=t.detailWorker.user)==null?void 0:E.personalData)==null?void 0:A.citizenship)),1)]),e("tr",null,[rt,e("td",lt,r(t.detailWorker.experience),1)]),e("tr",null,[nt,e("td",ct,[e("ul",null,[(c(!0),h(g,null,k((J=t.detailWorker.user)==null?void 0:J.welderHasSkills,(o,n)=>{var d;return c(),h("li",{key:n},r(l.getSkill((d=o.welderSkill)==null?void 0:d.skillName)),1)}),128))])])]),e("tr",null,[dt,e("td",ht,r(t.detailWorker.promote),1)]),e("tr",null,[_t,e("td",ut,[e("ul",null,[e("li",null,[e("a",{href:(U=(K=t.detailWorker.user)==null?void 0:K.personalData)==null?void 0:U.curriculumVitae,target:"_blank"},"CV",8,mt)]),(c(!0),h(g,null,k((O=t.detailWorker.user)==null?void 0:O.welderDocuments,(o,n)=>(c(),h("li",{key:n},[e("a",{href:o.documentPath,target:"_blank"},"Sertifikat Keahlian "+r(n+1),9,bt)]))),128))])])])])])])])]),e("div",pt,[e("button",{disabled:t.isLoading,type:"button",class:"btn btn-sm btn-secondary","data-bs-dismiss":"modal"}," Kembali ",8,gt),t.detailWorker.status==0?(c(),h("div",kt,[e("button",{disabled:t.isLoading,type:"button",class:"btn btn-sm btn-danger me-2",onClick:a[1]||(a[1]=o=>l.handleReject(t.detailWorker.user.uuid))}," Ditolak ",8,vt),e("button",{disabled:t.isLoading,type:"button",class:"btn btn-sm btn-success",onClick:a[2]||(a[2]=o=>l.handleAccept(t.detailWorker.user.uuid))}," Diterima ",8,ft)])):M("",!0)])])])]),m(Y,{msg:t.msg},null,8,["msg"])],64)}const Ct=ae(ne,[["render",yt],["__scopeId","data-v-52557e6d"]]);export{Ct as default};
