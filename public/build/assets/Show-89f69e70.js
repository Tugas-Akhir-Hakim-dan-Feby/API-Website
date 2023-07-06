import{P as Y}from"./PageTitle-6608e5c7.js";import{d as D}from"./id-03dff7fa.js";import{L as N}from"./Loader-9414f264.js";import{_ as j,r as a,o as s,c as n,b as c,w as d,a as e,g as A,f as H,t as I,d as i,F as U,l as E}from"./app-18f778da.js";const K={props:["id"],data(){return{isLoading:!1,user:{}}},mounted(){this.getUser()},methods:{getUser(){this.isLoading=!0,this.$store.dispatch("showData",["user/expert",this.id]).then(o=>{this.isLoading=!1,this.user=o.data}).catch(o=>{this.isLoading=!1})},getCreatedAt(o){return D(o).locale("id").format("DD MMMM YYYY")},getDateBirth(o){return D(o).locale("id").format("DD MMMM YYYY")},checkFile(o){let _=window.location.origin+"/storage";return!!(o&&o.length>_.length)},onBack(){this.$router.push({name:"User Expert"})}},components:{PageTitle:Y,Loader:N}},V={class:"d-flex"},O={key:0,class:"btn btn-sm btn-primary mb-1 me-2"},z={class:"breadcrumb m-0"},R={class:"breadcrumb-item"},G={class:"breadcrumb-item"},q=e("li",{class:"breadcrumb-item active"},"Detail Pakar",-1),J={class:"row"},Q={class:"col-12 position-relative"},W={class:"card"},X={class:"card-body"},Z={class:"row"},$={class:"col-lg-5"},ee={class:"text-center d-block mb-4"},te=["src","alt"],se=e("div",{class:"d-lg-flex d-none justify-content-center"},null,-1),ne={class:"col-lg-7"},oe={class:"ps-lg-4"},re=["innerHTML"],ie={class:"mb-1"},le={class:"mt-3"},ae=e("h6",{class:"font-14"},"NIK:",-1),ce=["innerHTML"],de={class:"mt-3"},_e=e("h6",{class:"font-14"},"Email:",-1),he=["innerHTML"],ue={class:"mt-3"},me={class:"row"},fe={class:"col-md-6"},pe=e("h6",{class:"font-14"}," Asal Instansi: ",-1),ke=["innerHTML"],ge={class:"col-md-6"},be=e("h6",{class:"font-14"}," Tempat, Tanggal Lahir: ",-1),we=["innerHTML"],Me={class:"table-responsive mt-4"},xe={class:"table table-bordered table-centered mb-0"},ye=e("thead",{class:"table-light"},[e("tr",null,[e("th",null,"Dokumen"),e("th")])],-1),ve=e("td",null," Sertifikat Kompetensi Bidang Pengelasan Tingkat Nasional atau International ",-1),Le=["href"],Pe=e("i",{class:"mdi mdi-download"},null,-1),Te={key:1},Be=e("td",null," Riwayat Pengalaman Bekerja atau Penelitian Bidang Pengelasan 10 Tahun Terakhir ",-1),De=["href"],He=e("i",{class:"mdi mdi-download"},null,-1),Ue={key:1},Fe=e("td",null," Surat Keterangan Aktif Bekerja Dalam Bidang Pengelasan ",-1),Ce=["href"],Se=e("i",{class:"mdi mdi-download"},null,-1),Ye={key:1},Ne=e("td",null," Memiliki Sertifikat atau Ijazah Gelar Profesi ",-1),je=["href"],Ae=e("i",{class:"mdi mdi-download"},null,-1),Ie={key:1},Ee=e("td",null,"Ijazah Pendidikan Formal",-1),Ke=["href"],Ve=e("i",{class:"mdi mdi-download"},null,-1),Oe={key:1},ze=e("td",null," Sertifikat Kompetensi Pengelasan Lainnya ",-1),Re=["href"],Ge=e("i",{class:"mdi mdi-download"},null,-1);function qe(o,_,Je,Qe,t,r){var u,m,f,p,k,g,b,w,M,x,y,v,L,P,T,B;const h=a("router-link"),F=a("PageTitle"),C=a("Loader");return s(),n(U,null,[c(F,{title:"Detail Pakar "+t.user.name,isBack:!0,onOnBack:r.onBack},{default:d(()=>{var l;return[e("div",V,[((l=t.user.expert)==null?void 0:l.status)=="NOT-APPROVED"?(s(),n("button",O," Konfirmasi ")):H("",!0),e("ol",z,[e("li",R,[c(h,{to:{name:"Dashboard"}},{default:d(()=>[i("Dashboard")]),_:1})]),e("li",G,[c(h,{to:{name:"User Expert"}},{default:d(()=>[i("Pakar")]),_:1})]),q])])]}),_:1},8,["title","onOnBack"]),e("div",J,[e("div",Q,[t.isLoading?(s(),A(C,{key:0})):H("",!0),e("div",W,[e("div",X,[e("div",Z,[e("div",$,[e("div",ee,[e("img",{src:t.user.welderMember?(u=t.user.welderMember)==null?void 0:u.pasPhoto:`https://ui-avatars.com/api/?background=random&size=280&rounded=false&length=2&name=${t.user.name}`,class:"img-fluid",style:{"max-width":"280px"},alt:t.user.name,onerror:"this.src=null; this.src='https://www.salonlfc.com/wp-content/uploads/2018/01/image-not-found-scaled.png'"},null,8,te)]),se]),e("div",ne,[e("form",oe,[e("h3",{class:"mt-0",innerHTML:t.user.name},null,8,re),e("p",ie," Terdaftar: "+I(r.getCreatedAt((m=t.user.expert)==null?void 0:m.createdAt)),1),e("div",le,[ae,e("p",{innerHTML:(f=t.user.welderMember)==null?void 0:f.residentIdCard},null,8,ce)]),e("div",de,[_e,e("p",{innerHTML:t.user.email},null,8,he)]),e("div",ue,[e("div",me,[e("div",fe,[pe,e("p",{innerHTML:(p=t.user.expert)==null?void 0:p.instance},null,8,ke)]),e("div",ge,[be,e("p",{innerHTML:((k=t.user.welderMember)==null?void 0:k.birthPlace)+", "+r.getDateBirth((g=t.user.welderMember)==null?void 0:g.dateBirth)},null,8,we)])])])])])]),e("div",Me,[e("table",xe,[ye,e("tbody",null,[e("tr",null,[ve,e("td",null,[r.checkFile((b=t.user.expert)==null?void 0:b.certificateCompetency)?(s(),n("a",{key:0,href:(w=t.user.expert)==null?void 0:w.certificateCompetency,target:"_blank"},[Pe,i(" Unduh")],8,Le)):(s(),n("p",Te,"belum tersedia"))])]),e("tr",null,[Be,e("td",null,[r.checkFile((M=t.user.expert)==null?void 0:M.career)?(s(),n("a",{key:0,href:(x=t.user.expert)==null?void 0:x.career,target:"_blank"},[He,i(" Unduh")],8,De)):(s(),n("p",Ue,"belum tersedia"))])]),e("tr",null,[Fe,e("td",null,[r.checkFile((y=t.user.expert)==null?void 0:y.workingMail)?(s(),n("a",{key:0,href:(v=t.user.expert)==null?void 0:v.workingMail,target:"_blank"},[Se,i(" Unduh")],8,Ce)):(s(),n("p",Ye,"belum tersedia"))])]),e("tr",null,[Ne,e("td",null,[r.checkFile((L=t.user.expert)==null?void 0:L.certificateProfession)?(s(),n("a",{key:0,href:(P=t.user.expert)==null?void 0:P.certificateProfession,target:"_blank"},[Ae,i(" Unduh")],8,je)):(s(),n("p",Ie,"belum tersedia"))])]),e("tr",null,[Ee,e("td",null,[r.checkFile((T=t.user.expert)==null?void 0:T.certificateSchool)?(s(),n("a",{key:0,href:(B=t.user.welderMember)==null?void 0:B.certificateSchool,target:"_blank"},[Ve,i(" Unduh")],8,Ke)):(s(),n("p",Oe,"belum tersedia"))])]),(s(!0),n(U,null,E(t.user.welderDocuments,(l,S)=>(s(),n("tr",{key:S},[ze,e("td",null,[e("a",{href:l.documentPath,target:"_blank"},[Ge,i(" Unduh")],8,Re)])]))),128))])])])])])])])],64)}const et=j(K,[["render",qe]]);export{et as default};
