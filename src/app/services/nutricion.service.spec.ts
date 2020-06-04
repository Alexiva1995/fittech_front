import { TestBed } from '@angular/core/testing';

import { NutricionService } from './nutricion.service';

describe('NutricionService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: NutricionService = TestBed.get(NutricionService);
    expect(service).toBeTruthy();
  });
});
