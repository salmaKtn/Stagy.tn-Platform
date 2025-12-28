import { Component, OnInit } from '@angular/core';
import { NgbRatingConfig, NgbRating } from '@ng-bootstrap/ng-bootstrap';
//import * as jobs from '../../../shared/data/jon-search/job-serach'
import { RouterLink } from '@angular/router';
//import { JobFilterComponent } from '../job-filter/job-filter.component';
import { BreadcrumbComponent } from '../../../shared/components/breadcrumb/breadcrumb.component';
import { OfferService } from '../../../services/offer.service';

@Component({
    selector: 'app-list-view',
    templateUrl: './list-view.component.html',
    styleUrls: ['./list-view.component.scss'],
    imports: [BreadcrumbComponent, /*JobFilterComponent,*/ RouterLink, NgbRating]
})
export class ListViewComponent implements OnInit {

 // public jobCard = jobs.jobCards
  jobCards : any [] = []
  constructor( public config: NgbRatingConfig ,private offerService: OfferService) {
    config.max = 5;
    config.readonly = true;
    
   // this.jobCards = this.jobCard.slice(0,8)
   }


  ngOnInit(): void {this.offerService.getAllOffers().subscribe({
    next: (offers) => {
      this.jobCards = offers.map((offer: any) => ({
        id: offer.id,
        title: offer.title,
        rating: Math.floor(Math.random() * 5) + 1, // Temporary dummy rating
        iconImg: 'assets/images/job-search/1.jpg', // Placeholder icon
        subTitle: `Company ID: ${offer.company}`, // You can replace this with real company name if you fetch it
        dace: `Applications: ${offer.applications.length}`, // Just an example
        para: `Eligibility: ${offer.eligibility} | Process: ${offer.process}`,
        NewTag: 'New', // You can randomize or conditionally apply
        tag: false,
        ribbon: true,
        day: 'Today',
      }));
    },
    error: (err) => console.error('Error fetching offers', err),
  });
  }

}
