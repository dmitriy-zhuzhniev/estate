<?php

use Illuminate\Support\Facades\URL;

Admin::model('App\Apartment')->title('Объекты')->display(function ()
{
	$display = AdminDisplay::datatables();
	$display->with();
	$display->filters([

	]);
	$display->columns([
		Column::string('title')->label('Title'),
		Column::string('type')->label('Type'),
		Column::string('realty_id')->label('Realty ID'),
//		Column::string('customer')->label('Customer'),
//		Column::string('owner')->label('Owner'),
//		Column::string('agreement_id')->label('Agreement ID'),
//		Column::string('realty_goal')->label('Realty goal'),
//		Column::string('region')->label('Region'),
		Column::string('city')->label('City'),
//		Column::string('house_number')->label('House number'),
//		Column::string('apartment_number')->label('Apartment number'),
		Column::string('square')->label('Square'),
		Column::string('floor')->label('Floor'),
		Column::string('total_floor')->label('Total floor'),
		Column::string('rooms')->label('Rooms'),
		Column::string('user_id')->label('Manager ID'),
        Column::action('parse')->value('Search')->icon('fa-search')->url('/apartment/:id/parse'),
	]);
	return $display;
})->createAndEdit(function ()
{
	$form = AdminForm::form();
	$form->items([
		FormItem::text('title', 'Title'),
        FormItem::select('type', 'Type')->enum(['apartment', 'house', 'parcel', 'garage']),
		FormItem::text('realty_id', 'Realty ID'),
		FormItem::text('customer', 'Customer'),
		FormItem::text('owner', 'Owner'),
		FormItem::text('agreement_id', 'Agreement ID'),
		FormItem::text('realty_goal', 'Realty goal'),
		FormItem::text('region', 'Region'),
		FormItem::text('city', 'City'),
		FormItem::text('house_number', 'House number'),
		FormItem::text('apartment_number', 'Apartment number'),
		FormItem::text('square', 'Square'),
		FormItem::text('floor', 'Floor'),
		FormItem::text('total_floor', 'Total Floor'),
		FormItem::text('rooms', 'Rooms'),
        FormItem::select('user_id', 'Manager'),
	]);
	return $form;
});