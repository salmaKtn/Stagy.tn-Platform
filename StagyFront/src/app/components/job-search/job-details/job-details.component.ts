import { Component, OnInit } from '@angular/core';
import { RouterLink } from '@angular/router';
import { NgbRating, NgbRatingConfig } from '@ng-bootstrap/ng-bootstrap';
import { BreadcrumbComponent } from '../../../shared/components/breadcrumb/breadcrumb.component';
//import * as jobs from '../../../shared/data/jon-search/job-serach';
//import { JobFilterComponent } from '../job-filter/job-filter.component';
//import { JobApplyComponent } from './job-apply/job-apply.component';
import { OfferService } from '../../../services/offer.service';
import { ActivatedRoute } from '@angular/router';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule } from '@angular/forms';



@Component({
    selector: 'app-job-details',
    templateUrl: './job-details.component.html',
    styleUrls: ['./job-details.component.scss'],
    imports: [BreadcrumbComponent/*, JobFilterComponent, JobApplyComponent*/, RouterLink, NgbRating, ReactiveFormsModule, CommonModule,]
})
export class JobDetailsComponent implements OnInit {

  public jobCards: any [] = []
  id: any;

  
  constructor(public config: NgbRatingConfig,private offerService: OfferService,  private route: ActivatedRoute) { 
    config.max = 5;
    config.readonly = true;
   }

  ngOnInit(): void {
    this.id = this.route.snapshot.params['id'];
    this.offerService.getOfferByID(this.id).subscribe({next: (offer) => {
      this.jobCards = [{
                id: offer.id,
                title: offer.title,
                rating: Math.floor(Math.random() * 5) + 1,
                iconImg: 'assets/images/job-search/1.jpg',
                subTitle: `Company ID: ${offer.company}`,
                dace: `Applications: ${offer.applications.length}`,
                para: `Eligibility: ${offer.eligibility} | Process: ${offer.process}`,
                NewTag: 'New',
                tag: false,
                ribbon: true,
                day: 'Today',
            }];
    },
    error: (err) => console.error('Error fetching offers', err),
  

    });
  }

}
