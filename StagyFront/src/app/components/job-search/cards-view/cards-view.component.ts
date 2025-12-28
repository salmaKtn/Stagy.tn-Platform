import { Component, OnInit } from '@angular/core';
import { NgbRatingConfig, NgbRating } from '@ng-bootstrap/ng-bootstrap';
//import * as jobs from '../../../shared/data/jon-search/job-serach'
import { RouterLink } from '@angular/router';
import { JobFilterComponent } from '../job-filter/job-filter.component';
import { BreadcrumbComponent } from '../../../shared/components/breadcrumb/breadcrumb.component';
import { OfferService } from '../../../services/offer.service';


@Component({
    selector: 'app-cards-view',
    templateUrl: './cards-view.component.html',
    styleUrls: ['./cards-view.component.scss'],
    imports: [BreadcrumbComponent, JobFilterComponent, RouterLink, NgbRating,
]
})
export class CardsViewComponent implements OnInit {
  public jobCards: any[] = []; 


  constructor( public config: NgbRatingConfig,private offerService: OfferService) {
    config.max = 7;
    config.readonly = true;
   }

  ngOnInit(): void {
  this.offerService.getAllOffers().subscribe({
    next: (offers) => {
          console.log('Fetched offers:', offers);  // <-- Log raw data here

      this.jobCards = offers.map((offer: any) => ({
        id: offer.id,
        title: offer.title,
        rating: Math.floor(Math.random() * 5) + 1, // Temporary dummy rating
        iconImg: 'assets/images/job-search/1.jpg', // Placeholder icon
        company:offer.companyName,
        payment:offer.payment,
        location:offer.location,
        duration:offer.duration,
        type: offer.type, // You can randomize or conditionally apply
        tag: false,
        ribbon: true,
      }));
    },
    error: (err) => console.error('Error fetching offers', err),
  });
}

}
