<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\City;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Product;
use App\Models\Supplier;
use Box\Spout\Writer\Style\StyleBuilder;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

class ImportExportController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Support\Collection
     */
    public function fileImportExport()
    {
        return view('fileimportexport');
    }

    public function productFileImportExport()
    {
        return view('productimportexport');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function customerFileImport(Request $request)
    {
//        dd($request->all());
        $collection = (new FastExcel)->import($request->file, function ($line) {
            if ($line['Email'] == "") {
                $email = 'no-reply@rwn.com';
            } else {
                $email = $line['Email'];
            }
            $customer_record = Customer::where('customer_code', $line['Customer_Code'])->first();
            if (isset($customer_record)) {
                $customer_info = $customer_record->update(
                    ['customer_code' => $line['Customer_Code'],
                        'customer_name' => $line['Customer_Name'],
                        'customer_phone_number' => $line['Phone_Number'],
                        'customer_contact' => $line['Contact_Name'],
                        'customer_email' => $email,
                        'account_manager_id' => 0,
                    ]);
                $city = City::where('name', $line['Bill_Add_City'])->first();
                if (isset($city)) {
                    $province_id = $city['province_id'];
                } else {
                    $province_id = 0;
                }
                $customer_address = CustomerAddress::where('customer_id', $customer_record->id)->first();
                if (isset($customer_address)) {
                    CustomerAddress::where('customer_id', $customer_record->id)->update([
                        'customer_address' => $line['Bill_Add_St_Name1'],
                        'customer_address_line2' => $line['Bill_Add_St_Name2'],
                        'customer_address_city' => $line['Bill_Add_City'],
                        'customer_address_postal_code' => $line['Bill_Add_zip'],
                        'province_id' => $province_id,
                        'customer_address_type' => 1,
                    ]);
                } else {
                    CustomerAddress::create([
                        'customer_id' => $customer_record->id,
                        'customer_address' => $line['Bill_Add_St_Name1'],
                        'customer_address_line2' => $line['Bill_Add_St_Name2'],
                        'customer_address_city' => $line['Bill_Add_City'],
                        'customer_address_postal_code' => $line['Bill_Add_zip'],
                        'province_id' => $province_id,
                        'customer_address_type' => 1,
                    ]);
                }

                if ($line['Ship_as_Bill'] == 'N') {
                    $city = City::where('name', $line['Ship_Add_City'])->first();
                    if (isset($city)) {
                        $province_id = $city['province_id'];
                    } else {
                        $province_id = 0;
                    }
                    $customer_address = CustomerAddress::create([
                        'customer_id' => $customer_record->id,
                        'customer_address' => $line['Ship_Add_St_Name1'],
                        'customer_address_line2' => $line['Ship_Add_St_Name2'],
                        'customer_address_city' => $line['Ship_Add_City'],
                        'customer_address_postal_code' => $line['Ship_Add_zip'],
                        'province_id' => $province_id,
                        'customer_address_type' => 2,
                    ]);
                }
            } else {
                $data = Customer::create(
                    ['customer_code' => $line['Customer_Code'],
                        'customer_name' => $line['Customer_Name'],
                        'customer_phone_number' => $line['Phone_Number'],
                        'customer_contact' => $line['Contact_Name'],
                        'customer_email' => $email,
                        'account_manager_id' => 0,
                    ]);
                $city = City::where('name', $line['Bill_Add_City'])->first();
                if (isset($city)) {
                    $province_id = $city['province_id'];
                } else {
                    $province_id = 0;
                }
                CustomerAddress::create([
                    'customer_id' => $data->id,
                    'customer_address' => $line['Bill_Add_St_Name1'],
                    'customer_address_line2' => $line['Bill_Add_St_Name2'],
                    'customer_address_city' => $line['Bill_Add_City'],
                    'customer_address_postal_code' => $line['Bill_Add_zip'],
                    'province_id' => $province_id,
                    'customer_address_type' => 1,
                ]);

                if ($line['Ship_as_Bill'] == 'N') {
                    $city = City::where('name', $line['Ship_Add_City'])->first();
                    if (isset($city)) {
                        $province_id = $city['province_id'];
                    } else {
                        $province_id = 0;
                    }
                    CustomerAddress::create([
                        'customer_id' => $data->id,
                        'customer_address' => $line['Ship_Add_St_Name1'],
                        'customer_address_line2' => $line['Ship_Add_St_Name2'],
                        'customer_address_city' => $line['Ship_Add_City'],
                        'customer_address_postal_code' => $line['Ship_Add_zip'],
                        'province_id' => $province_id,
                        'customer_address_type' => 2,
                    ]);
                }
            }


        });
        return back();
    }

    /**
     * @return \Illuminate\Support\Collection|string|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function fileExport()
    {
        $customers = Customer::with('customerAddress')
            ->get();
        $header_style = (new StyleBuilder())->setFontBold()->build();

        $rows_style = (new StyleBuilder())
            ->setFontSize(15)
            ->setShouldWrapText()
            ->setBackgroundColor("EDEDED")
            ->build();
        //  dd($customers);
        return (new FastExcel($customers))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->download('customers.csv');

    }

    public function productFileImport(Request $request)
    {
        if($request->hasFile('product_file')) {
            $collection = (new FastExcel)->import($request->product_file, function ($line) {
                if (!array_filter($line)) {
                } else {
                    $brand = Brand::firstOrCreate(
                        ['brand_name' => $line['Brand_Name']],
                        ['status_id' => 1]
                    );
                    $category = Category::firstOrCreate(
                        ['category_name' => $line['Category_Name']],
                        ['status_id' => 1,
                            'category_code' => $line['Category_Code']
                        ]);
                    $supplier = Supplier::where('supplier_code', $line['Supplier_Code'])->first();
                    if (isset($supplier)) {
                        $supplier_id = $supplier->id;
                    } elseif (isset($line['Supplier_Name']) && isset($line['Supplier_Code'])) {
                        $supplier_create = Supplier::create([
                            'supplier_name' => $line['Supplier_Name'],
                            'status_id' => 1,
                            'supplier_code' => $line['Supplier_Code']
                        ]);
                        $supplier_id = $supplier_create->id;
                    } else {
                        $supplier_id = 0;
                    }
                    if (empty($line['Price by unit'])) {
                        $price = (!empty($line['Price by case']) ? $line['Price by case'] : 0);
                    } else {
                        $price = $line['Price by unit'];
                    }
                    $product = Product::updateOrCreate(
                        ['product_code' => $line['Product_ID']],
                        [
                            'product_status' => 1,
                            'supplier_id' => $supplier_id,
                            'category_id' => $category->id,
                            'brand_id' => $brand->id,
                            'product_title' => (!empty($line['Product_Title']) ? $line['Product_Title'] : $line['Product_Description']),
                            'Product_Description' => (empty($line['Product_Title']) ? '' : $line['Product_Description']),
                            'product_price' => $price,
                            'product_position' => (!empty($line['Tile_no']) ? $line['Tile_no'] : 0),
                            'product_featured' => ($line['Featured'] == 'Y' || $line['Featured'] == 'y') ? 1 : 0,
                            'product_seasonal_promotion' => ($line['Seasonal_Promotion'] == 'Y' || $line['Seasonal_Promotion'] == 'y') ? 1 : 0,

                        ]
                    );
                }
            });

            return back();
        }
        return back();


    }

    public function fileExportProduct()
    {

    }
}
