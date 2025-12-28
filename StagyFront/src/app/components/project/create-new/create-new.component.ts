import { Component, OnInit } from "@angular/core";
import { FormsModule } from '@angular/forms';
import { DropzoneConfigInterface, DropzoneModule } from 'ngx-dropzone-wrapper';
import { BreadcrumbComponent } from "../../../shared/components/breadcrumb/breadcrumb.component";
import { OfferService } from "../../../services/offer.service";
import { UserService } from "../../../services/user.service";

@Component({
  selector: "app-create-new",
  standalone: true,
  templateUrl: "./create-new.component.html",
  styleUrls: ["./create-new.component.scss"],
  imports: [
    BreadcrumbComponent,
    DropzoneModule,
    FormsModule
  ]
})
export class CreateNewComponent implements OnInit {

  constructor(private offerService: OfferService, private userService: UserService) {}

  // 🌟 Form state
  offer: any = {
    title: '',
    location: '',
    type: '',
    category: '',
    startDate: '',
    description: '',
    endDate: '',
    duration: '',
    governorate: '',
    payment: null,
    workingHours: '',
    Roles: '',
    processStep: '' // ⬅️ New string field to hold comma-separated steps
  };

  skills: string[] = [];
  languages: string[] = [];
  fields: string[] = [];

  steps: { title: string }[] = [];

  companyID: number | null = null;
governorates = [
  'Ariana', 'Béja', 'Ben Arous', 'Bizerte', 'Gabès', 
  'Gafsa', 'Jendouba', 'Kairouan', 'Kasserine', 'Kébili',
  'Kef', 'Mahdia', 'Manouba', 'Médenine', 'Monastir',
  'Nabeul', 'Sfax', 'Sidi Bouzid', 'Siliana', 'Sousse',
  'Tataouine', 'Tozeur', 'Tunis', 'Zaghouan'
];
  ngOnInit(): void {
    this.userService.getCompany().subscribe({

      next: (res: any) => {
        this.companyID = res.id;
      },
      error: (err) => {
        console.error("Error fetching company ID", err);
      }
    });
  }

  // ✅ Step management
  addStep() {
    this.steps.push({ title: '' });
  }

  removeStep(index: number) {
    this.steps.splice(index, 1);
  }

  // ✅ Tag management
  addTag(event: any, type: 'skills' | 'languages' | 'fields') {
    const input = event.target as HTMLInputElement;
    const value = input.value.trim();
    if (value) {
      if (type === 'skills' && this.skills.length < 3) this.skills.push(value);
      if (type === 'languages' && this.languages.length < 3) this.languages.push(value);
      if (type === 'fields' && this.fields.length < 3) this.fields.push(value);
    }
    input.value = '';
    event.preventDefault();
  }

  removeTag(index: number, type: 'skills' | 'languages' | 'fields') {
    if (type === 'skills') this.skills.splice(index, 1);
    if (type === 'languages') this.languages.splice(index, 1);
    if (type === 'fields') this.fields.splice(index, 1);
  }

  // ✅ Final submit
submitOffer() {
  if (!this.companyID) {
    console.error("Company ID not loaded!");
    return alert("Company ID not loaded!");
  }

  // Prepare the offer data
  const offerData = {
    ...this.offer,
    company: this.companyID,
    skills: this.skills.join(','),
    languages: this.languages.join(','),
    fieldsOfInterest: this.fields.join(','),
    processStep: this.steps.map(step => step.title).join(',')
  };

  // Log the complete payload before sending
  console.log("Submitting offer data:", JSON.stringify(offerData, null, 2));

  this.offerService.createOffer(offerData).subscribe({
    next: (res: any) => {
      console.log("Offer created successfully:", res);
      alert("✅ Offer submitted successfully!");
    },
    error: (err) => {
      console.error("Offer creation failed:", err);
      if (err.error) {
        console.error("Server response:", err.error);
      }
      alert(`Offer creation failed: ${err.message || 'Unknown error'}`);
    }
  });
}

public config: DropzoneConfigInterface = {
    // Change this to your upload POST address:
    url: 'https://httpbin.org/post',
    clickable: true,
    addRemoveLinks: true,
    parallelUploads: 1,
  };

  public text = '<i class="icon-cloud-up"></i><h6>Glisser une image du stage.</h6>';



  onUploadError(args: any): void {
    console.log("onUploadError:", args);
  }

  onUploadSuccess(args: any): void {
    console.log("onUploadSuccess:", args);
  }
}
