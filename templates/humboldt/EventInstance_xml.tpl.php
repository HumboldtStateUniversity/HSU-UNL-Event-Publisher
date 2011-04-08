	<Event>
        <EventID><?php echo $this->event->id; ?></EventID>
        <EventTitle><?php echo htmlspecialchars($this->event->title); ?></EventTitle>
        <EventSubtitle><?php echo htmlspecialchars($this->event->subtitle); ?></EventSubtitle>
        <?php 
        $startu = strtotime($this->eventdatetime->starttime);
		$endu = strtotime($this->eventdatetime->endtime);
		?>
        <DateTime>
            <StartDate><?php echo date('Y-m-d', $startu); ?></StartDate>
            <StartTime><?php echo date('H:i:s', $startu); ?>Z</StartTime>
            <?php if (isset($this->eventdatetime->endtime)
                    && !empty($this->eventdatetime->endtime)
                    && ($endu > $startu)) : ?>
            <EndDate><?php echo date('Y-m-d', $endu); ?></EndDate>
            <EndTime><?php echo date('H:i:s', $endu); ?>Z</EndTime>
            <?php endif; ?>
        </DateTime>
        <Locations>
        	<?php
			if ($this->eventdatetime->location_id) : 
			$loc = $this->eventdatetime->getLink('location_id');
			?>
            <Location>
                <LocationID><?php echo $loc->id; ?></LocationID>
                <LocationName><?php echo htmlspecialchars($loc->name); ?></LocationName>
                <LocationTypes>
                    <LocationType><?php echo $loc->type; ?></LocationType>
                </LocationTypes>
                <Address>
                    <Room><?php echo htmlspecialchars($this->eventdatetime->room); ?></Room>
                    <BuildingName><?php echo htmlspecialchars($loc->name); ?></BuildingName>
                    <CityName><?php echo htmlspecialchars($loc->city); ?></CityName>
                    <PostalZone><?php echo $loc->zip; ?></PostalZone>
                    <CountrySubentityCode><?php echo $loc->state; ?></CountrySubentityCode>
                    <Country>
                        <IdentificationCode xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-1.0" codeListID="ISO3166-1" codeListAgencyID="6" codeListAgencyName="United Nations Economic Commission for Europe" codeListName="Country" codeListVersionID="0.3" languageID="en" codeListURI="http://www.iso.org/iso/en/prods-services/iso3166ma/02iso-3166-code-lists/list-en1-semic.txt" codeListSchemeURI="urn:oasis:names:specification:ubl:schema:xsd:CountryIdentificationCode-1.0">US</IdentificationCode>
                        <Name xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-1.0">United States</Name>
                    </Country>
                </Address>
                <Phones>
                    <Phone>
                        <PhoneNumber><?php echo $loc->phone; ?></PhoneNumber>
                    </Phone>
                </Phones>

                <WebPages>
                    <WebPage>
                        <Title>Location Web Page</Title>
                        <URL><?php echo htmlspecialchars($loc->webpageurl); ?></URL>
                    </WebPage>
                </WebPages>
                <MapLinks>
                    <MapLink><?php echo htmlspecialchars($loc->mapurl); ?></MapLink>
                </MapLinks>

                <LocationHours><?php echo htmlspecialchars($loc->hours); ?></LocationHours>
                <Directions><?php echo htmlspecialchars($loc->directions); ?></Directions>
                <AdditionalPublicInfo><?php echo htmlspecialchars($loc->additionalpublicinfo); ?></AdditionalPublicInfo>
            </Location>
            <?php endif; ?>
        </Locations>
        <?php
        $etype = UNL_UCBCN::factory('event_has_eventtype');
        $etype->event_id = $this->event->id;
        if ($etype->find()) : ?>
        <EventTypes>
        	<?php while ($etype->fetch()) : 
        		$type = $etype->getLink('eventtype_id');
	        	if (!PEAR::isError($type)) : ?>
	            <EventType>
	                <EventTypeID><?php echo $type->id; ?></EventTypeID>
	                <EventTypeName><?php echo htmlspecialchars($type->name); ?></EventTypeName>
	                <EventTypeDescription><?php echo htmlspecialchars($type->description); ?></EventTypeDescription>
	            </EventType>
	            <?php 
            	endif;
            endwhile; ?>
        </EventTypes>
        <?php endif; ?>
        <Languages>
            <Language>en-US</Language>
        </Languages>
        <EventTransparency><?php echo $this->event->transparency; ?></EventTransparency>

        <Description><?php echo htmlspecialchars($this->event->description); ?></Description>
        <ShortDescription><?php echo htmlspecialchars($this->event->shortdescription); ?></ShortDescription>
        <Refreshments><?php echo htmlspecialchars($this->event->refreshments); ?></Refreshments>
        <WebPages>
            <WebPage>
                <Title>Event Instance URL</Title>
                <URL><?php echo htmlspecialchars($this->url); ?></URL>
            </WebPage>
            <?php if (!empty($this->event->webpageurl)): ?>
            <WebPage>
                <Title>Event webpage</Title>
                <URL><?php echo htmlspecialchars($this->event->webpageurl); ?></URL>
            </WebPage>
            <?php endif; ?>
        </WebPages>
        <?php
        $webcast = UNL_UCBCN::factory('webcast');
        $webcast->event_id = $this->event->id;
        if ($webcast->find()): ?>
        <Webcasts>
        	<?php while ($webcast->fetch()) : ?>
            <Webcast>
                <Title><?php echo htmlspecialchars($webcast->title); ?></Title>
                <WebcastStatus><?php echo $webcast->status; ?></WebcastStatus>
                <DateAvailable><?php echo date('Y-m-d',strtotime($webcast->dateavailable)); ?></DateAvailable>
                <PlayerType><?php echo $webcast->playertype; ?></PlayerType>
                <Bandwidth><?php echo $webcast->bandwidth; ?></Bandwidth>
                <?php
                $webcastlink = UNL_UCBCN::factory('webcastlink');
                $webcastlink->webcast_id = $webcast->id;
                if ($webcastlink->find()) : ?>
                <WebcastURLs>
                	<?php while ($webcastlink->fetch()) : ?>
                    <WebcastURL>
                        <URL><?php echo $webcastlink->url; ?></URL>
                        <SequenceNumber><?php echo $webcastlink->sequencenumber; ?></SequenceNumber>
                    </WebcastURL>
                    <?php endwhile; ?>
                </WebcastURLs>
                <?php endif; ?>
                <WebcastAdditionalInfo><?php echo htmlspecialchars($webcast->additionalinfo); ?></WebcastAdditionalInfo>
            </Webcast>
            <?php endwhile; ?>
        </Webcasts>
        <?php endif; ?>
        <?php if (isset($this->event->imagedata)) : ?>
        <Images>
            <Image>
                <Title>Image</Title>
                <Description>image for event <?php echo $this->event->id; ?></Description>
                <URL><?php echo UNL_UCBCN_Frontend::formatURL(array()); ?>?image&amp;id=<?php echo $this->event->id; ?></URL>
            </Image>
        </Images>
        <?php endif; ?>
        <?php
        $document = UNL_UCBCN::factory('document');
        $document->event_id = $this->event->id;
        if ($document->find()) : ?>
        <Documents>
        	<?php while ($document->fetch()) : ?>
            <Document>
                <Title><?php echo htmlspecialchars($document->name); ?></Title>
                <URL><?php echo $document->url; ?></URL>
            </Document>
            <?php endwhile; ?>
        </Documents>
        <?php endif; ?>
        <?php
        $contact = UNL_UCBCN::factory('publiccontact');
        $contact->event_id = $this->event->id;
        if ($contact->find()) : ?>
        <PublicEventContacts>
        	<?php while ($contact->fetch()) : ?>
            <PublicEventContact>
                <PublicEventContactID><?php echo $contact->id; ?></PublicEventContactID>

                <ContactName>
                    <FullName><?php echo htmlspecialchars($contact->name); ?></FullName>
                </ContactName>
                <ProfessionalAffiliations>
                    <ProfessionalAffiliation>

                        <JobTitles>
                            <JobTitle><?php echo htmlspecialchars($contact->jobtitle); ?></JobTitle>
                        </JobTitles>
                        <OrganizationName><?php echo htmlspecialchars($contact->organization); ?></OrganizationName>
                        <OrganizationWebPages>
                            <WebPage>

                                <Title><?php echo htmlspecialchars($contact->name); ?></Title>
                                <URL><?php echo $contact->webpageurl; ?></URL>
                            </WebPage>

                        </OrganizationWebPages>
                    </ProfessionalAffiliation>
                </ProfessionalAffiliations>
                <Phones>
                    <Phone>
                        <PhoneNumber><?php echo htmlspecialchars($contact->phone); ?>/PhoneNumber>
                    </Phone>
                </Phones>

                <EmailAddresses>
                    <EmailAddress><?php echo $contact->emailaddress; ?></EmailAddress>
                </EmailAddresses>
                <Addresses>
                    <Address>
                        <StreetName><?php echo htmlspecialchars($contact->addressline1); ?></StreetName>
                        <AdditionalStreetName><?php echo htmlspecialchars($contact->addressline2); ?></AdditionalStreetName>
                        <Room><?php echo htmlspecialchars($contact->room); ?></Room>
                        <CityName><?php echo htmlspecialchars($contact->city); ?></CityName>
                        <PostalZone><?php echo $contact->zip; ?></PostalZone>
                        <CountrySubentityCode><?php echo htmlspecialchars($contact->State); ?></CountrySubentityCode>
                        <Country>
                            <IdentificationCode xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-1.0" codeListID="ISO3166-1" codeListAgencyID="6" codeListAgencyName="United Nations Economic Commission for Europe" codeListName="Country" codeListVersionID="0.3" languageID="en" codeListURI="http://www.iso.org/iso/en/prods-services/iso3166ma/02iso-3166-code-lists/list-en1-semic.txt" codeListSchemeURI="urn:oasis:names:specification:ubl:schema:xsd:CountryIdentificationCode-1.0">US</IdentificationCode>

                            <Name xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-1.0">United States</Name>
                        </Country>
                    </Address>
                </Addresses>
                <WebPages>
                    <WebPage>
                        <Title><?php echo htmlspecialchars($contact->name); ?></Title>
                        <URL><?php echo $contact->webpageurl; ?></URL>
                    </WebPage>
                </WebPages>
            </PublicEventContact>
            <?php endwhile; ?>
        </PublicEventContacts>
        <?php endif; ?>
        <EventListingContacts>

            <EventListingContact>
                <ContactName>
                    <FullName><?php echo htmlspecialchars($this->event->listingcontactname); ?></FullName>
                </ContactName>
                <Phones>
                    <Phone>
                        <PhoneNumber><?php echo htmlspecialchars($this->event->listingcontactphone); ?></PhoneNumber>
                    </Phone>
                </Phones>
                <EmailAddresses>
                    <EmailAddress><?php echo $this->event->listingcontactemail; ?></EmailAddress>
                </EmailAddresses>
            </EventListingContact>
        </EventListingContacts>
        <EventStatus>Happening As Scheduled</EventStatus>
        <Classification>Public</Classification>
        <?php if (!empty($this->event->privatecomment)): ?>
        <PrivateComments>
            <PrivateComment><?php echo htmlspecialchars($this->event->privatecomment); ?></PrivateComment>
        </PrivateComments>
        <?php endif; ?>
    </Event>
