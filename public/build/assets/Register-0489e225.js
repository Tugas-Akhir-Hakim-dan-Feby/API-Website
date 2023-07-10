import{P as y}from"./PageTitle-78e8a2ae.js";import{P as x}from"./Pagination-548a5c76.js";import{S as v}from"./Success-8789b882.js";import{C as L}from"./Confirm-5d1ab401.js";import{L as E}from"./Loader-0f5ef10c.js";import{P as T}from"./pagination-8c4016f1.js";import{d as c}from"./id-62a5f779.js";import{_ as M,r as l,o as n,c as o,b as m,a as e,g as _,f as d,F as g,l as S,j as C,d as j}from"./app-4e61fff3.js";const B={data(){return{examPackets:[],errors:{},pagination:{perPage:10,page:1},filters:{search:""},metaPagination:{},roles:[],msg:"",titleExamPacket:"",idExamPacket:"",uuid:null,isLoading:!1,isError:!1,isRegistered:!1}},mounted(){this.getExamPackets(),this.getUser()},methods:{iteration(t){return T.iteration(t,this.metaPagination)},getUser(){this.$store.dispatch("showData",["user","me"]).then(t=>{this.roles=t.roles}).catch(t=>{})},getExamPackets(){this.isLoading=!0;let t=[`per_page=${this.pagination.perPage}`,`page=${this.pagination.page}`,`search=${this.filters.search}`];t.push(["sort_direction=desc"]),this.$store.dispatch("getData",["exam-packet",t.join("&")]).then(s=>{this.isLoading=!1,this.examPackets=s.data,this.metaPagination=s.meta}).catch(s=>{this.isLoading=!1})},getCheckExamPacket(t){return this.$store.dispatch("showData",["user-exam-packet/check",t]).then(s=>{this.isRegistered=s}),this.isRegistered},getSchedule(t){return c(t).locale("id").format("DD MMMM YYYY")},handleSubmit(){this.isLoading=!0;const t={examPacketId:this.idExamPacket};this.$store.dispatch("postData",["user-exam-packet",t]).then(s=>{this.isLoading=!1,$("#registerExam").modal("hide"),$("#successModal").modal("show"),this.msg="Registrasi berhasil. Silahkan periksa email anda untuk mengetahui info selebihnya.",this.getUser()}).catch(s=>{this.isLoading=!1})},onPageChange(t){this.pagination.page=t,this.getExamPackets()},checkSchedule(t,s){let h=c();return t=c(t).locale("id"),!!h.isBefore(t,"day")},onBack(){this.$router.push({name:"Exam Packet"})}},components:{PageTitle:y,Success:v,Pagination:x,Confirm:L,Loader:E}},U={class:"card position-relative"},D={class:"card-body"},H={class:"d-md-flex d-block justify-content-between align-items-center mb-2"},w=e("div",{class:"text-center"},null,-1),R={class:"d-md-flex justify-content-between align-items-center"},N={class:"table-responsive"},K={class:"table table-hover table-bordered"},O=e("thead",null,[e("tr",null,[e("th",null,"No."),e("th",null,"Uji Kompetensi"),e("th",null,"Jadwal Ujian"),e("th",null,"Alamat Tempat Ujian"),e("th")])],-1),V={key:0},Y=e("td",{colspan:"5",class:"text-center"}," Belum ada data uji kompetensi. ",-1),A=[Y],F=["innerHTML"],I=["innerHTML"],J=["innerHTML"],q=["innerHTML"],z={key:0,class:"badge border-0 btn-success ms-2",style:{cursor:"auto"}},G=["onClick"],Q={class:"modal fade",id:"registerExam",tabindex:"-1","aria-labelledby":"registerExamLabel","aria-hidden":"true","data-bs-backdrop":"static","data-bs-keyboard":"false"},W={class:"modal-dialog"},X={class:"modal-content"},Z=e("div",{class:"modal-header"},[e("h5",{class:"modal-title",id:"registerExamLabel"}," Registrasi Paket Uji Kompetensi ")],-1),ee={class:"modal-body"},te={class:"mb-3"},se=e("label",null,"Nama Peserta",-1),ae=["value"],ie={class:"mb-3"},ne=e("label",null,"Email Peserta",-1),oe=["value"],re={class:"mb-3"},le=e("label",null,"Nama Paket Uji Kompetensi",-1),de=["value"],ce={class:"modal-footer"},me=["disabled"],he={key:0,class:"btn btn-sm btn-primary"},ue={key:1,class:"btn btn-sm btn-primary",type:"button",disabled:""},_e=e("span",{class:"spinner-border spinner-border-sm me-1",role:"status","aria-hidden":"true"},null,-1);function ge(t,s,h,pe,a,r){const p=l("PageTitle"),b=l("Loader"),k=l("Pagination"),f=l("Success"),P=l("Confirm");return n(),o(g,null,[m(p,{title:"Daftar Uji Kompetensi",isBack:!0,onOnBack:r.onBack},null,8,["onOnBack"]),e("div",U,[a.isLoading?(n(),_(b,{key:0})):d("",!0),e("div",D,[e("div",H,[w,e("div",R,[t.$can("pagination","Exampacket")?(n(),_(k,{key:0,pagination:a.metaPagination,onOnPageChange:s[0]||(s[0]=i=>r.onPageChange(i))},null,8,["pagination"])):d("",!0)])]),e("div",N,[e("table",K,[O,e("tbody",null,[a.examPackets.length<1?(n(),o("tr",V,A)):(n(!0),o(g,{key:1},S(a.examPackets,(i,u)=>(n(),o("tr",{key:u},[e("th",{innerHTML:r.iteration(u)},null,8,F),e("td",{innerHTML:i.name},null,8,I),e("td",{innerHTML:r.getSchedule(i.schedule)},null,8,J),e("td",{innerHTML:i.practiceExamAddress},null,8,q),e("td",null,[r.getCheckExamPacket(i.uuid)?(n(),o("button",z," Terdaftar ")):(n(),o("button",{key:1,class:"btn btn-sm btn-primary","data-bs-toggle":"modal","data-bs-target":"#registerExam",onClick:be=>(a.titleExamPacket=i.name,a.idExamPacket=i.uuid)}," Daftar ",8,G))])]))),128))])])])])]),e("div",Q,[e("div",W,[e("div",X,[Z,e("form",{onSubmit:s[1]||(s[1]=C((...i)=>r.handleSubmit&&r.handleSubmit(...i),["prevent"])),method:"post"},[e("div",ee,[e("div",te,[se,e("input",{type:"text",class:"form-control",value:t.$store.state.USER.name,disabled:""},null,8,ae)]),e("div",ie,[ne,e("input",{type:"email",class:"form-control",value:t.$store.state.USER.email,disabled:""},null,8,oe)]),e("div",re,[le,e("input",{type:"text",class:"form-control",value:a.titleExamPacket,disabled:""},null,8,de)])]),e("div",ce,[e("button",{type:"button",class:"btn btn-sm btn-secondary me-2","data-bs-dismiss":"modal",disabled:a.isLoading}," Batal ",8,me),a.isLoading?d("",!0):(n(),o("button",he," Kirim ")),a.isLoading?(n(),o("button",ue,[_e,j(" Harap Tunggu... ")])):d("",!0)])],32)])])]),m(f,{msg:a.msg},null,8,["msg"]),m(P,{onOnDelete:t.onDelete},null,8,["onOnDelete"])],64)}const Te=M(B,[["render",ge]]);export{Te as default};