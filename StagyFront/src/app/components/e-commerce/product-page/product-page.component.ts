import { Component, OnInit } from '@angular/core';
import { Image, GalleryModule } from '@ks89/angular-modal-gallery';
import { DescriptionTabComponent } from './description-tab/description-tab.component';
import { BrandComponent } from './brand/brand.component';
import { DetailsComponent } from './details/details.component';
import { BreadcrumbComponent } from '../../../shared/components/breadcrumb/breadcrumb.component';
import { OfferService } from '../../../services/offer.service';
import { CardsViewComponent } from '../../job-search/cards-view/cards-view.component';
import { ReactiveFormsModule } from '@angular/forms';
import { ActivatedRoute } from '@angular/router';
import { UserService } from '../../../services/user.service';
@Component({
    selector: 'app-product-page',
    templateUrl: './product-page.component.html',
    styleUrls: ['./product-page.component.scss'],
    imports: [ ReactiveFormsModule,BreadcrumbComponent, GalleryModule, DetailsComponent, BrandComponent, DescriptionTabComponent, CardsViewComponent]
})
export class ProductPageComponent implements OnInit {
  constructor(private offerService:OfferService ,private route: ActivatedRoute, private userService : UserService) { }

offer : any;
company: any ;
companyID: number;
  ngOnInit(): void {
  const idParam = this.route.snapshot.params['id'];

  if (!idParam || isNaN(Number(idParam))) {
    console.error('❌ Invalid or missing ID param:', idParam);
    return;
  }

  const id = parseInt(idParam, 10); 
this.offerService.getOfferByID(id).subscribe({
    next: (res) => {
      this.offer = res;
      console.log('📦 Offer:', this.offer);
      this.companyID=this.offer.company
      this.userService.getCompanyByID(this.companyID).subscribe({
    next: (res) =>{this.company=res;},
    error:(err) =>{console.error('failed loading company',err);
      console.log(this.company.name);
      
    }});
    },
    error: (err) => {
      console.error('❌ Failed to load offer:', err);
    }
  });
  
  }

}
